<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'name',
        'sku',
        'unit',
        'min_stock',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function currentStock()
    {
        $in = (int) $this->movements()->where('type', 'in')->sum('quantity');
        $out = (int) $this->movements()->where('type', 'out')->sum('quantity');
        $adjust = (int) $this->movements()->where('type', 'adjust')->sum('quantity');

        return $in - $out + $adjust;
    }
}

