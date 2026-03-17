<?php

namespace App\Listeners;

use App\Events\BankDepositValidated;
use App\Support\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDepositValidatedNotifications implements ShouldQueue
{
    public function handle(BankDepositValidated $event): void
    {
        NotificationService::notifySubmitterOfDepositValidated($event->deposit);
    }
}
