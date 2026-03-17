<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDepositAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_deposit_id',
        'user_id',
        'action',
        'details',
    ];

    public function deposit()
    {
        return $this->belongsTo(BankDeposit::class, 'bank_deposit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
