@extends('layouts.admin')

@section('content')
<div class="max-w-[1600px] mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Inventaire / Produits
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Gestion du Catalogue
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium text-lg">
                {{ $products->total() }} produits enregistrés chez Connect Office.
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}"
            class="group flex items-center gap-3 bg-gradient-to-r from-brand-blue to-brand-green text-white px-8 py-4 rounded-2xl shadow-lg shadow-brand-blue/20 hover:shadow-brand-green/30 hover:scale-[1.02] active:scale-95 transition-all duration-300">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="font-bold tracking-wide">Nouveau Produit</span>
        </a>
    </div>

    <!-- FILTRES AVANCÉS -->
    <div class="bg-white dark:bg-darkCard border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] p-6 mb-8 shadow-sm">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">

            <!-- Barre de recherche -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Rechercher par nom ou SKU..."
                    class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl pl-12 pr-4 py-4 text-sm focus:ring-2 focus:ring-brand-blue/30 dark:text-white transition-all">
            </div>

            <!-- Filtre Catégorie -->
            <div class="w-full lg:w-72">
                <select name="category"
                    class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl px-6 py-4 text-sm focus:ring-2 focus:ring-brand-blue/30 dark:text-white transition-all cursor-pointer">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Bouton Filtrer -->
            <button type="submit"
                class="bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold transition-all shadow-md">
                Appliquer
            </button>

            <!-- Reset -->
            @if(request()->anyFilled(['search', 'category']))
            <a href="{{ route('admin.products.index') }}" class="flex items-center justify-center px-4 py-4 text-slate-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
            @endif
        </form>
    </div>

    <!-- TABLEAU DE PRODUITS -->
    <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-8 py-6">Produit & Détails</th>
                        <th class="px-8 py-6">Classification</th>
                        <th class="px-8 py-6">Prix (FCFA)</th>
                        <th class="px-8 py-6">Statut</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($products as $product)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all duration-300">

                        <!-- Nom & Image -->
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-inner group-hover:scale-110 transition-transform duration-300">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800 dark:text-white group-hover:text-brand-blue transition-colors leading-tight">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-[10px] font-mono text-slate-400 mt-1 uppercase tracking-widest">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Catégorie & Sous-catégorie -->
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-brand-green rounded-full"></span>
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $product->category->name ?? 'Non classé' }}</span>
                                </div>
                                @if($product->subcategory)
                                <p class="text-[11px] text-slate-400 pl-3.5">{{ $product->subcategory->name }}</p>
                                @endif
                            </div>
                        </td>

                        <!-- Prix -->
                        <td class="px-8 py-6 font-black text-slate-700 dark:text-slate-200">
                            {{ number_format($product->price, 0, ',', ' ') }} <span class="text-[10px] text-brand-green ml-1">FCFA</span>
                        </td>

                        <!-- Statut -->
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1.5">
                                @if($product->is_active)
                                <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-brand-green text-[10px] font-black uppercase tracking-wider">
                                    Actif
                                </span>
                                @else
                                <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 text-[10px] font-black uppercase tracking-wider">
                                    Inactif
                                </span>
                                @endif

                                @if($product->is_featured)
                                <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-500 text-[10px] font-black uppercase tracking-wider border border-amber-200 dark:border-amber-900/50">
                                    ★ Vedette
                                </span>
                                @endif
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-brand-blue hover:text-white rounded-xl transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="delete-form-{{ $product->id }}" class="hidden">
                                    @csrf @method('DELETE')
                                </form>

                                <button type="button"
                                    onclick="openDeleteModal({{ $product->id }})"
                                    class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h4 class="text-2xl font-black text-slate-800 dark:text-white">Aucun produit trouvé</h4>
                            <p class="text-slate-500 dark:text-slate-400 mt-2 italic font-medium">Réessayez avec d'autres filtres ou créez un nouveau produit.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION DESIGN -->
    @if($products->hasPages())
    <div class="mt-10 px-4">
        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
    </div>
    @endif

</div>
<!-- ... fin de ton tableau ... -->

<!-- MODAL DE CONFIRMATION (Inchangé) -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
            <div class="px-8 pt-8 pb-6">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 mx-auto bg-red-100 rounded-2xl dark:bg-red-900/20 sm:mx-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-6 sm:text-left">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase italic tracking-tighter">
                            Confirmer la suppression
                        </h3>
                        <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">
                            Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col-reverse gap-3 px-8 pb-8 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-4 text-sm font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl hover:bg-slate-200 transition-all">Annuler</button>
                <!-- On appelle la fonction de suppression directement au clic -->
                <button type="button" onclick="confirmDelete()" class="px-8 py-4 text-sm font-black text-white uppercase bg-red-500 rounded-2xl hover:bg-red-600 shadow-lg active:scale-95 transition-all">
                    Supprimer définitivement
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // On déclare la variable en dehors pour qu'elle soit accessible partout
    let productIdToDelete = null;

    function openDeleteModal(id) {
        console.log("Ouverture du modal pour l'ID:", id);
        productIdToDelete = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Bloque le scroll
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Rend le scroll
        productIdToDelete = null;
    }

    function confirmDelete() {
        if (productIdToDelete) {
            const formId = 'delete-form-' + productIdToDelete;
            const form = document.getElementById(formId);
            if (form) {
                console.log("Envoi du formulaire:", formId);
                form.submit();
            } else {
                console.error("Formulaire introuvable:", formId);
            }
        }
    }
</script>
@endsection