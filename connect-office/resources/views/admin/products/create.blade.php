@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up">

    <!-- HEADER AVEC FIL D'ARIANE -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-widest mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Inventaire / Produits / Nouveau
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Ajouter un produit
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" 
               class="px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm">
                Annuler
            </a>
            <button form="productForm" type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-lg shadow-brand-blue/20 hover:scale-[1.02] active:scale-95 transition-all">
                Publier le produit
            </button>
        </div>
    </div>

    <form action="{{ route('admin.products.store') }}" 
          id="productForm"
          method="POST" 
          enctype="multipart/form-data" 
          x-data="productFormHandler()"
          class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <!-- COLONNE GAUCHE (CONTENU PRINCIPAL) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Informations de base -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-10 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 20.414H8v-4.144L18.586 3.586z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Détails du produit</h2>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom du produit</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="Ex: Station de travail Dell Precision">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Description technique</label>
                        <textarea name="description" rows="8" required
                                  class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all resize-none"
                                  placeholder="Décrivez les spécifications techniques..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Organisation -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-10 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-green-50 dark:bg-green-900/20 text-brand-green rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Classification</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Catégorie parente</label>
                        <select name="category_id" x-model="selectedCategory" @change="loadSubcategories" required
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all appearance-none cursor-pointer">
                            <option value="">Sélectionner</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Sous-catégorie</label>
                        <select name="subcategory_id" x-model="selectedSubcategory" required
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all appearance-none cursor-pointer">
                            <option value="">Sélectionner</option>
                            <template x-for="sub in filteredSubcategories" :key="sub.id">
                                <option :value="sub.id" x-text="sub.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE (PARAMÈTRES ET MÉDIAS) -->
        <div class="space-y-8">
            
            <!-- Prix et SKU -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Prix de vente (FCFA)</label>
                        <div class="relative">
                            <input type="number" name="price" value="{{ old('price') }}"
                                   class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl pl-6 pr-16 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-bold transition-all">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">FCFA</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Référence (SKU)</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-400 font-mono text-sm transition-all"
                               placeholder="REF-000">
                    </div>
                </div>
            </div>

            <!-- Image du produit -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-4 ml-1">Visuel produit</label>
                
                <div class="relative group">
                    <input type="file" name="image" @change="previewImage" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div class="relative w-full h-64 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-brand-blue/50 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/50">
                        
                        <template x-if="!imagePreview">
                            <div class="text-center p-6">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 group-hover:text-brand-blue transition-colors">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-500">Glissez ou cliquez pour uploader</p>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase">JPG, PNG (Max. 2MB)</p>
                            </div>
                        </template>

                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                        </template>

                        <div x-show="imagePreview" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                            <span class="text-white text-xs font-bold bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl">Changer l'image</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visibilité -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6">Visibilité</h3>
                
                <div class="space-y-4">
                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="text-sm font-bold group-hover:text-brand-green transition-colors">Publier en ligne</span>
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                        </div>
                    </label>

                    <label class="flex items-center justify-between cursor-pointer group">
                        <span class="text-sm font-bold group-hover:text-brand-blue transition-colors">Mettre à la une</span>
                        <div class="relative">
                            <input type="checkbox" name="is_featured" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-blue"></div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function productFormHandler() {
    return {
        selectedCategory: '{{ old('category_id') }}',
        selectedSubcategory: '{{ old('subcategory_id') }}',
        imagePreview: null,
        subcategories: @json($categories->flatMap->subcategories),

        get filteredSubcategories() {
            return this.subcategories.filter(sub => sub.category_id == this.selectedCategory);
        },

        loadSubcategories() {
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