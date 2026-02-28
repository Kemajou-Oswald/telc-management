<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type', // Ajouté car présent dans ta validation
        'company_name',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    // Relation avec le produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}