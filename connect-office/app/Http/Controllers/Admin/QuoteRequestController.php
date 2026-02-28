<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuoteRequest;

class QuoteRequestController extends Controller
{
    /**
     * Afficher la liste des demandes de devis
     */
    public function index()
    {
        $quotes = QuoteRequest::with('product')
            ->latest()
            ->paginate(10);

        return view('admin.quotes.index', compact('quotes'));
    }

    /**
     * Afficher le détail d'une demande
     */
    public function show(QuoteRequest $quote)
    {
        return view('admin.quotes.show', compact('quote'));
    }

    /**
     * Mettre à jour le statut (new → pending → processed)
     */
    public function update(Request $request, QuoteRequest $quote)
    {
        $request->validate([
            'status' => 'required|in:new,pending,processed'
        ]);

        $quote->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('admin.quotes.index')
            ->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Supprimer une demande
     */
    public function destroy(QuoteRequest $quote)
    {
        $quote->delete();

        return redirect()
            ->route('admin.quotes.index')
            ->with('success', 'Demande supprimée.');
    }
}
