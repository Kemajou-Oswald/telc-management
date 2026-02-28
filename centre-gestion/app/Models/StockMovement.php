<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'centre_id',
        'type',
        'quantity',
        'label',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

