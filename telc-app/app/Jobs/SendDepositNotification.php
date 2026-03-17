<?php

namespace App\Jobs;

use App\Models\BankDeposit;
use App\Support\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDepositNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deposit;
    public $type;

    public function __construct(BankDeposit $deposit, string $type)
    {
        $this->deposit = $deposit;
        $this->type = $type;
    }

    public function handle(): void
    {
        if ($this->type === 'pending') {
            NotificationService::notifySuperAdminsOfDeposit($this->deposit);
        } elseif ($this->type === 'validated') {
            NotificationService::notifySubmitterOfDepositValidated($this->deposit);
        }
    }
}
