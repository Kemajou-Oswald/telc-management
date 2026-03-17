<?php

namespace App\Listeners;

use App\Events\BankDepositPendingCreated;
use App\Support\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDepositPendingNotifications implements ShouldQueue
{
    public function handle(BankDepositPendingCreated $event): void
    {
        NotificationService::notifySuperAdminsOfDeposit($event->deposit);
    }
}
