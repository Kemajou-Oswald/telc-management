@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-green font-bold text-xs uppercase tracking-widest mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 20.414H8v-4.144L18.586 3.586z"/></svg>
                Édition / {{ $product->sku ?? 'Produit' }}
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Modifier le produit
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Mise à jour des spécifications techniques et visuelles.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" 
               class="px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm">
                Annuler
            </a>
            <button form="editProductForm" type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-lg shadow-brand-blue/20 hover:scale-[1.02] active:scale-95 transition-all">
                Enregistrer les modifications
            </button>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" 
          id="editProductForm"
          method="POST" 
          enctype="multipart/form-data" 
          x-data="editProductHandler()"
          class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')

        <!-- COLONNE GAUCHE (CONTENU PRINCIPAL) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Bloc Informations -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-10 border border-slate-200/50 dark:border-slate-800/50 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-brand-blue/5 rounded-full -mr-16 -mt-16"></div>
                
                <div class="flex items-center gap-3 mb-8 relative">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Informations générales</h2>
                </div>

                <div class="space-y-6 relative">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Désignation du produit</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Fiche technique détaillée</label>
                        <textarea name="description" rows="8" required
                                  class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all resize-none">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bloc Classification -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-10 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-green-50 dark:bg-green-900/20 text-brand-green rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Classification</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Catégorie</label>
                        <select name="category_id" x-model="selectedCategory" @change="loadSubcategories" required
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all appearance-none cursor-pointer">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Sous-catégorie</label>
                        <select name="subcategory_id" x-model="selectedSubcategory" required
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all appearance-none cursor-pointer">
                            <template x-for="sub in filteredSubcategories" :key="sub.id">
                                <option :value="sub.id" x-text="sub.name" :selected="sub.id == selectedSubcategory"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE (MÉDIAS ET STATUS) -->
        <div class="space-y-8">
            
            <!-- Bloc Prix & SKU -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Prix de vente (FCFA)</label>
                        <div class="relative">
                            <input type="number" name="price" value="{{ old('price', $product->price) }}"
                                   class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl pl-6 pr-16 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-bold transition-all">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-brand-green font-bold text-xs">FCFA</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Référence SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                               class="w-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-slate-500 font-mono text-sm focus:outline-none"
                               placeholder="REF-000">
                    </div>
                </div>
            </div>

            <!-- Bloc Gestion Image -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Visuel du produit</label>
                
                <div class="space-y-4">
                    <!-- Image Actuelle -->
                    @if($product->image)
                    <div class="relative group h-48 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover grayscale-[20%] group-hover:grayscale-0 transition-all">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                            <span class="text-[10px] text-white font-bold uppercase tracking-widest">Image actuelle</span>
                        </div>
                    </div>
                    @endif

                    <!-- Zone de remplacement -->
                    <div class="relative group">
                        <input type="file" name="image" @change="previewImage" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="relative w-full h-32 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center transition-all group-hover:border-brand-blue group-hover:bg-blue-50/30 dark:group-hover:bg-blue-900/10">
                            
                            <template x-if="!imagePreview">
                                <div class="text-center">
                                    <svg class="w-6 h-6 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase">Remplacer l'image</p>
                                </div>
                            </template>

                            <template x-if="imagePreview">
                                <div class="w-full h-full p-2">
                                    <img :src="imagePreview" class="w-full h-full object-cover rounded-xl">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bloc Visibilité (Noir Premium) -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-brand-green rounded-full animate-pulse"></span>
                    Statut & Visibilité
                </h3>
                
                <div class="space-y-5">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="text-sm font-bold group-hover:text-brand-green transition-colors">Produit actif</span>
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="text-sm font-bold group-hover:text-brand-blue transition-colors">Mettre à la une</span>
                        <div class="relative">
                            <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-blue"></div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function editProductHandler() {
    return {
        selectedCategory: '{{ old('category_id', $product->category_id) }}',
        selectedSubcategory: '{{ old('subcategory_id', $product->subcategory_id) }}',
        imagePreview: null,
        subcategories: @json($categories->flatMap->subcategories),

        get filteredSubcategories() {
            return this.subcategories.filter(sub => sub.category_id == this.selectedCategory);
        },

        loadSubcategories() {
            // Optionnel : ne pas vider si c'est la catégorie actuelle au chargement
            this.selectedSubcategory = '';
        },

        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        }
    }
}
</script>
@endsection