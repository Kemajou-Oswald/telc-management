<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'centre_id',
        'title',
        'amount',
        'category',
        'expense_date',
        'month',
        'year'
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
}
