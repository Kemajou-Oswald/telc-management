<?php

namespace App\Events;

use App\Models\BankDeposit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BankDepositPendingCreated
{
    use Dispatchable, SerializesModels;

    public BankDeposit $deposit;

    public function __construct(BankDeposit $deposit)
    {
        $this->deposit = $deposit;
    }
}
