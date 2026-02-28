<?php

namespace App\Http\Controllers;

use App\Models\CashBook;
use App\Models\CashTransaction;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Ajoute cet import en haut du fichier

class StudentPaymentController extends Controller
{
    public function show(Student $student)
    {
        $user = auth()->user();

        if ($user->role !== 'super_admin' && $student->centre_id !== $user->centre_id) {
            abort(403);
        }

        $tuitionFees = TuitionFee::with('level')
            ->where(function ($q) use ($student) {
                $q->whereNull('centre_id')
                    ->orWhere('centre_id', $student->centre_id);
            })
            ->where('is_active', true)
            ->orderBy('language')
            ->orderBy('level_id')
            ->get();

        $payments = $student->payments()
            ->orderByDesc('payment_date')
            ->orderByDesc('created_at')
            ->get();

        // On calcule le total payé pour la scolarité (scolarite + mensualite)
        $totalPaid = $student->payments()
            ->whereIn('type', ['scolarite', 'mensualite', 'inscription'])
            ->sum('amount');

        $activeFee = $student->getActiveTuitionFee();

        return view('students.payments', compact(
            'student',
            'tuitionFees',
            'payments',
            'totalPaid',
            'activeFee'
        ));
    }

    public function store(Request $request, Student $student)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'tuition_fee_id' => 'required|exists:tuition_fees,id',
            'mode' => 'nullable|string',
        ]);

        $fee = TuitionFee::findOrFail($request->tuition_fee_id);
        $inputAmount = (float) $request->amount;

        // 1. CALCUL DE L'ÉTAT ACTUEL (Sur ce programme précis)
        $paidSoFarTotal = $student->payments()->where('tuition_fee_id', $fee->id)->sum('amount');
        $paidSoFarReg   = $student->payments()->where('tuition_fee_id', $fee->id)->sum('amount_registration');

        $totalCoursePrice = (float) $fee->total_amount;
        $regPriceRequired = (float) $fee->inscription_fee;

        // 2. SÉCURITÉ : Vérifier si on ne dépasse pas le prix total du cours
        $remainingGlobal = $totalCoursePrice - $paidSoFarTotal;
        if ($inputAmount > $remainingGlobal) {
            return back()->with('error', "Impossible : Le montant versé (" . number_format($inputAmount, 0, ',', ' ') . ") dépasse le solde restant du cours (" . number_format($remainingGlobal, 0, ',', ' ') . " FCFA).");
        }

        // 3. LOGIQUE DE VENTILATION INTELLIGENTE
        $amountReg = 0;
        $amountScol = 0;

        // Reste-t-il de l'inscription à payer ?
        $regGap = max(0, $regPriceRequired - $paidSoFarReg);

        if ($regGap > 0) {
            // On comble d'abord l'inscription
            $amountReg = min($inputAmount, $regGap);
            // Le reste part en scolarité
            $amountScol = $inputAmount - $amountReg;
        } else {
            // Inscription déjà OK, tout va en scolarité
            $amountReg = 0;
            $amountScol = $inputAmount;
        }

        // 4. GÉNÉRATION DE RÉFÉRENCE UNIQUE
        $reference = 'REC-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));

        // 5. DÉTERMINATION DU TYPE POUR L'AFFICHAGE
        $type = 'mensualite';
        if ($amountReg > 0 && $amountScol > 0) $type = 'mixte';
        elseif ($amountReg > 0) $type = 'inscription';

        // 6. ENREGISTREMENT
        $payment = Payment::create([
            'student_id'          => $student->id,
            'centre_id'           => $student->centre_id,
            'tuition_fee_id'      => $fee->id,
            'amount'              => $inputAmount,
            'amount_registration' => $amountReg,
            'amount_tuition'      => $amountScol,
            'type'                => $type,
            'mode'                => $request->mode ?? 'Espèces',
            'reference'           => $reference,
            'payment_date'        => now(),
            'month'               => now()->format('m'),
            'year'                => now()->format('Y'),
        ]);

        // 7. MISE À JOUR DE LA FICHE ÉTUDIANT
        $student->update(['tuition_fee_id' => $fee->id]);

        // 8. ENREGISTREMENT EN CAISSE
        $this->recordInCashBook($student, $payment);

        return redirect()->route('students.payments.show', $student)
            ->with('success', "Encaissement de " . number_format($inputAmount, 0, ',', ' ') . " FCFA validé. Réf: $reference")
            ->with('open_receipt', $payment->id);
    }

    protected function recordInCashBook(Student $student, Payment $payment)
    {
        // On ne référence que les entrées (encaissements)
        $centreId = $student->centre_id;
        $date = $payment->payment_date ?: now()->toDateString();

        // Récupérer ou créer la caisse du jour pour ce centre
        $cashBook = CashBook::where('centre_id', $centreId)
            ->whereDate('date', $date)
            ->first();

        if (!$cashBook) {
            $previous = CashBook::where('centre_id', $centreId)
                ->whereDate('date', '<', $date)
                ->orderByDesc('date')
                ->first();

            $soldeVeille = $previous ? $previous->solde_final : 0;

            $cashBook = CashBook::create([
                'centre_id' => $centreId,
                'date' => $date,
                'solde_veille' => $soldeVeille,
            ]);
        }

        if ($cashBook->is_closed) {
            return;
        }

        CashTransaction::create([
            'cash_book_id' => $cashBook->id,
            'centre_id' => $centreId,
            'direction' => 'entree',
            'amount' => $payment->amount,
            'label' => 'Versement étudiant ' . $student->first_name . ' ' . $student->last_name . ' (' . $payment->type . ')',
            'mode' => $payment->mode,
            'reference' => $payment->reference,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
        ]);
    }

    public function downloadReceipt(Payment $payment)
    {
        $student = $payment->student;
        $fee = $payment->tuitionFee;

        // Conversion du logo en Base64 pour DomPDF
        $path = public_path('images/logo_tara.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
            'student' => $student,
            'fee' => $fee,
            'logo' => $logoBase64,
            'centre' => $payment->centre,
            // Solde restant au moment du reçu
            'balance' => $student->getTuitionBalance()
        ])->setPaper('a5', 'portrait');

        return $pdf->stream('Recu_TARA_' . $payment->reference . '.pdf');
    }
}
