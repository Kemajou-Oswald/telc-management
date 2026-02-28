<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
class Subcategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
