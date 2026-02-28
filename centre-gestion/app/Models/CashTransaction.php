<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_book_id',
        'centre_id',
        'direction',
        'amount',
        'label',
        'mode',
        'reference',
        'source_type',
        'source_id',
        'is_cancelled',
        'cancelled_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_cancelled' => 'boolean',
        'cancelled_at' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(CashBook::class, 'cash_book_id');
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
}

