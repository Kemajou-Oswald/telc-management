<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index(Request $request) // Ajout de Request pour la recherche
    {
        // On commence la requête avec les relations et le décompte des produits
        $query = Subcategory::with('category')->withCount('products');

        // On gère la recherche si le champ 'search' est rempli dans la vue
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $subcategories = $query->latest()->paginate(10);

        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (Subcategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Subcategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id
        ]);

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Sous-catégorie créée avec succès.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();

        return view('admin.subcategories.edit',
            compact('subcategory', 'categories')
        );
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (
            Subcategory::where('slug', $slug)
                ->where('id', '!=', $subcategory->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $subcategory->update([
            'name' => $request->name,
            'slug' => $slug,
            'category_id' => $request->category_id
        ]);

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Sous-catégorie mise à jour.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return redirect()
            ->route('admin.subcategories.index')
            ->with('success', 'Sous-catégorie supprimée.');
    }
}