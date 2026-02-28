<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\QuoteRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalQuotes = QuoteRequest::count();
        $newQuotes = QuoteRequest::where('status', 'new')->count();

        // Devis par mois
        $quotesPerMonth = QuoteRequest::select(
                DB::raw("MONTH(created_at) as month"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy("month")
            ->orderBy("month")
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalQuotes',
            'newQuotes',
            'quotesPerMonth'
        ));
    }
}
