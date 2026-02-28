<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Product::with('centre');

        if ($user->role !== 'super_admin') {
            $query->where('centre_id', $user->centre_id)
                ->orWhereNull('centre_id');
        }

        $products = $query->orderBy('name')->get();

        $movesQuery = StockMovement::with(['product', 'creator', 'centre'])
            ->orderByDesc('created_at');

        if ($user->role !== 'super_admin') {
            $movesQuery->where('centre_id', $user->centre_id);
        }

        $movements = $movesQuery->limit(50)->get();

        return view('stock.index', compact('products', 'movements'));
    }

    public function storeProduct(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'unit' => ['nullable', 'string', 'max:50'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
        ]);

        Product::create([
            'centre_id' => $user->centre_id,
            'name' => $validated['name'],
            'sku' => isset($validated['sku']) ? $validated['sku'] : null,
            'unit' => isset($validated['unit']) ? $validated['unit'] : 'pièce',
            'min_stock' => isset($validated['min_stock']) ? $validated['min_stock'] : 0,
        ]);

        return back()->with('success', 'Produit ajouté au stock.');
    }

    public function storeMovement(Request $request, Product $product)
    {
        $user = auth()->user();

        if ($user->role !== 'super_admin' && $product->centre_id !== $user->centre_id) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', 'in:in,out,adjust'],
            'quantity' => ['required', 'integer'],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        StockMovement::create([
            'product_id' => $product->id,
            'centre_id' => $product->centre_id,
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'label' => isset($validated['label']) ? $validated['label'] : null,
            'created_by' => $user->id,
        ]);

        return back()->with('success', 'Mouvement de stock enregistré.');
    }
}

