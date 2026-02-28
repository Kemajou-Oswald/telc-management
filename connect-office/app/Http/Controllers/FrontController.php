<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    // Page d'accueil
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.home.index', compact('featuredProducts'));
    }
    public function solutions()
    {
        return view('solutions');
    }



    // Page Catalogue
    public function catalogue()
    {
        // Produits à la une
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        // Catégories avec sous-catégories et produits actifs
        $categories = Category::with([
            'subcategories.products' => function ($query) {
                $query->where('is_active', true);
            }
        ])->get();

        return view('catalogue', compact('featuredProducts', 'categories'));
    }

    // Page Détail Produit
    public function productShow($slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.catalogue.product-show', compact('product'));
    }
}
