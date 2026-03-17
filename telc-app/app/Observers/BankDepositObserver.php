<?php

namespace App\Observers;

use App\Jobs\SendDepositNotification;
use App\Models\BankDeposit;
use App\Models\User;

class BankDepositObserver
{
    public function created(BankDeposit $deposit): void
    {
        if ($deposit->status !== BankDeposit::STATUS_PENDING) {
            return;
        }

        $submitter = $deposit->relationLoaded('submitter')
            ? $deposit->submitter
            : $deposit->submitter()->first();

        if (! $submitter || $submitter->role !== User::ROLE_SECRETARY) {
            return;
        }

        // Envoi asynchrone après la réponse (sans bloquer)
        SendDepositNotification::dispatch($deposit, 'pending')->afterResponse();
    }

    public function updated(BankDeposit $deposit): void
    {
        if (! $deposit->wasChanged('status')) {
            return;
        }

        $previousStatus = $deposit->getOriginal('status');

        if ($previousStatus !== BankDeposit::STATUS_PENDING || $deposit->status !== BankDeposit::STATUS_VALIDATED) {
            return;
        }

        $submitter = $deposit->relationLoaded('submitter')
            ? $deposit->submitter
            : $deposit->submitter()->first();

        if (! $submitter || $submitter->role !== User::ROLE_SECRETARY) {
            return;
        }

        // Envoi asynchrone après la réponse (sans bloquer)
        SendDepositNotification::dispatch($deposit, 'validated')->afterResponse();
    }
}
