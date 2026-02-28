<?php

namespace App\Http\Controllers;

use App\Models\CashBook;
use App\Models\CashTransaction;
use Illuminate\Http\Request;

class CashBookController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $cashBooks = CashBook::with('centre')
            ->forUserCentre($user)
            ->orderByDesc('date')
            ->limit(30)
            ->get();

        return view('cash_books.index', compact('cashBooks'));
    }

    /**
     * Créer (ou ouvrir) la caisse du jour pour le centre de l'utilisateur.
     */
    public function openToday()
    {
        $user = auth()->user();

        $today = now()->toDateString();

        $existing = CashBook::where('centre_id', $user->centre_id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return redirect()->route('cash.show', $existing);
        }

        // On va chercher le solde de la veille s'il existe
        $previous = CashBook::where('centre_id', $user->centre_id)
            ->whereDate('date', '<', $today)
            ->orderByDesc('date')
            ->first();

        // Compatibilité versions PHP < 8 (pas de nullsafe operator)
        $soldeVeille = $previous ? $previous->solde_final : 0;

        $cashBook = CashBook::create([
            'centre_id' => $user->centre_id,
            'date' => $today,
            'solde_veille' => $soldeVeille,
        ]);

        return redirect()->route('cash.show', $cashBook);
    }

    public function show(CashBook $cashBook)
    {
        $user = auth()->user();

        if ($user->role !== 'super_admin' && $cashBook->centre_id !== $user->centre_id) {
            abort(403);
        }

        $transactions = $cashBook->transactions()
            ->orderBy('created_at')
            ->get();

        $totalEntrees = $transactions
            ->where('direction', 'entree')
            ->where('is_cancelled', false)
            ->sum('amount');

        $totalSorties = $transactions
            ->where('direction', 'sortie')
            ->where('is_cancelled', false)
            ->sum('amount');

        $soldeFinal = $cashBook->solde_veille + $totalEntrees - $totalSorties;

        return view('cash_books.show', compact(
            'cashBook',
            'transactions',
            'totalEntrees',
            'totalSorties',
            'soldeFinal'
        ));
    }

    public function storeTransaction(Request $request, CashBook $cashBook)
    {
        $user = auth()->user();

        if ($user->role !== 'super_admin' && $cashBook->centre_id !== $user->centre_id) {
            abort(403);
        }

        if ($cashBook->is_closed) {
            return back()->with('error', 'La journée est clôturée, impossible d’ajouter une transaction.');
        }

        $data = $request->validate([
            'direction' => 'required|in:entree,sortie',
            'amount' => 'required|numeric|min:0',
            'label' => 'required|string|max:255',
            'mode' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:100',
        ]);

        $data['centre_id'] = $cashBook->centre_id;

        CashTransaction::create([
            'cash_book_id' => $cashBook->id,
            'centre_id' => $cashBook->centre_id,
            'direction' => $data['direction'],
            'amount' => $data['amount'],
            'label' => $data['label'],
            'mode' => $data['mode'] ?? null,
            'reference' => $data['reference'] ?? null,
        ]);

        return back()->with('success', 'Transaction ajoutée à la caisse.');
    }

    public function close(CashBook $cashBook)
    {
        $user = auth()->user();

        if ($user->role !== 'super_admin' && $cashBook->centre_id !== $user->centre_id) {
            abort(403);
        }

        if ($cashBook->is_closed) {
            return back()->with('info', 'La journée est déjà clôturée.');
        }

        $transactions = $cashBook->transactions()
            ->where('is_cancelled', false)
            ->get();

        $totalEntrees = $transactions->where('direction', 'entree')->sum('amount');
        $totalSorties = $transactions->where('direction', 'sortie')->sum('amount');
        $soldeFinal = $cashBook->solde_veille + $totalEntrees - $totalSorties;

        $cashBook->update([
            'total_entrees' => $totalEntrees,
            'total_sorties' => $totalSorties,
            'solde_final' => $soldeFinal,
            'date_cloture' => now(),
            'is_closed' => true,
        ]);

        return back()->with('success', 'Journée de caisse clôturée.');
    }

    public function cancelTransaction(CashTransaction $transaction)
    {
        $cashBook = $transaction->book;
        $user = auth()->user();

        if ($user->role !== 'super_admin' && $cashBook->centre_id !== $user->centre_id) {
            abort(403);
        }

        if ($cashBook->is_closed) {
            return back()->with('error', 'Impossible d’annuler une transaction après clôture.');
        }

        if ($transaction->is_cancelled) {
            return back()->with('info', 'Cette transaction est déjà annulée.');
        }

        $transaction->update([
            'is_cancelled' => true,
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Transaction annulée (non supprimée).');
    }
}

