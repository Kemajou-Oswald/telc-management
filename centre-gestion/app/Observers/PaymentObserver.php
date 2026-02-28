<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\CashBook;
use App\Models\CashTransaction;
use Carbon\Carbon;

class PaymentObserver
{
    /**
     * À la création d'un paiement, on enregistre
     * automatiquement une entrée dans la caisse journalière.
     */
    public function created(Payment $payment): void
    {
        // On ignore les paiements vides
        if (!$payment->amount || $payment->amount <= 0) {
            return;
        }

        // Centre du paiement (priorité à centre_id sur la ligne de paiement)
        $centreId = $payment->centre_id;
        if (!$centreId) {
            if ($payment->relationLoaded('student')) {
                $student = $payment->student;
            } else {
                $student = $payment->student;
            }
            if ($student) {
                $centreId = $student->centre_id;
            }
        }

        if (!$centreId) {
            // Impossible de rattacher à une caisse
            return;
        }

        // Date de la caisse = date du paiement (ou aujourd'hui par défaut)
        $date = $payment->payment_date
            ? Carbon::parse($payment->payment_date)->toDateString()
            : Carbon::today()->toDateString();

        // On récupère ou crée le CashBook du centre pour cette date
        $cashBook = CashBook::where('centre_id', $centreId)
            ->whereDate('date', $date)
            ->first();

        if (!$cashBook) {
            // Chercher la caisse de la veille pour récupérer son solde final
            $previous = CashBook::where('centre_id', $centreId)
                ->where('date', '<', $date)
                ->orderBy('date', 'desc')
                ->first();

            $soldeVeille = $previous ? (float) $previous->solde_final : 0.0;

            $cashBook = CashBook::create([
                'centre_id' => $centreId,
                'date' => $date,
                'solde_veille' => $soldeVeille,
                'total_entrees' => 0,
                'total_sorties' => 0,
                'solde_final' => $soldeVeille,
                'date_cloture' => null,
                'is_closed' => false,
            ]);
        }

        // On ne touche pas à une caisse clôturée
        if ($cashBook->is_closed) {
            return;
        }

        // Préparation du libellé : "Versement de Prénom Nom (Réf: XXX)"
        $student = $payment->relationLoaded('student')
            ? $payment->student
            : $payment->student()->first();

        $fullName = $student && $student->full_name
            ? $student->full_name
            : 'Étudiant';

        $reference = $payment->reference ?: 'N/A';

        CashTransaction::create([
            'cash_book_id' => $cashBook->id,
            'centre_id' => $centreId,
            'direction' => 'entree',
            'amount' => $payment->amount,
            'label' => "Versement de {$fullName} (Réf: {$reference})",
            'mode' => $payment->mode,
            'reference' => $payment->reference,
            'source_type' => Payment::class,
            'source_id' => $payment->id,
            'is_cancelled' => false,
            'cancelled_at' => null,
        ]);

        // Recalcul des totaux de la caisse
        $totalEntrees = (float) $cashBook->transactions()
            ->where('direction', 'entree')
            ->where('is_cancelled', false)
            ->sum('amount');

        $totalSorties = (float) $cashBook->transactions()
            ->where('direction', 'sortie')
            ->where('is_cancelled', false)
            ->sum('amount');

        $cashBook->update([
            'total_entrees' => $totalEntrees,
            'total_sorties' => $totalSorties,
            'solde_final' => $cashBook->solde_veille + $totalEntrees - $totalSorties,
        ]);
    }
}

