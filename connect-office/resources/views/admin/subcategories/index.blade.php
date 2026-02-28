@extends('layouts.admin')

@section('content')
<div class="max-w-[1600px] mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-green font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Inventaire / Classification
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Gestion des sous-catégories
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium text-lg">
                Organisez la profondeur de votre catalogue produit.
            </p>
        </div>

        <a href="{{ route('admin.subcategories.create') }}"
            class="group flex items-center gap-3 bg-gradient-to-r from-brand-blue to-brand-green text-white px-8 py-4 rounded-2xl shadow-lg shadow-brand-blue/20 hover:shadow-brand-green/30 hover:scale-[1.02] active:scale-95 transition-all duration-300">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="font-bold tracking-wide">Nouvelle Sous-catégorie</span>
        </a>
    </div>

    <!-- ZONE DE RECHERCHE -->
    <div class="bg-white dark:bg-darkCard border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] p-6 mb-8 shadow-sm">
        <form method="GET" class="flex flex-col lg:flex-row gap-4 text-sm font-medium">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Filtrer par nom..."
                    class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl pl-6 pr-4 py-4 focus:ring-2 focus:ring-brand-blue/30 dark:text-white transition-all">
            </div>
            <button type="submit" class="bg-slate-800 dark:bg-slate-700 text-white px-8 py-4 rounded-2xl font-bold hover:bg-slate-900 transition-all">
                Rechercher
            </button>
        </form>
    </div>

    <!-- TABLEAU DE GESTION -->
    <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-8 py-6">Sous-catégorie</th>
                        <th class="px-8 py-6">Appartient à (Parent)</th>
                        <th class="px-8 py-6 text-center">Volume</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($subcategories as $subcategory)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-brand-blue group-hover:bg-brand-blue group-hover:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <span class="text-base font-bold text-slate-800 dark:text-white tracking-tight">
                                    {{ $subcategory->name }}
                                </span>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <span class="px-4 py-1.5 rounded-xl bg-green-50 dark:bg-green-900/20 text-brand-green text-xs font-black uppercase tracking-widest border border-brand-green/10">
                                {{ $subcategory->category->name ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="px-8 py-6 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-lg font-black text-slate-700 dark:text-slate-200">{{ $subcategory->products_count ?? '0' }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Produits liés</span>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}"
                                   class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-brand-blue hover:text-white rounded-xl transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>

                                <form action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" method="POST" id="delete-form-{{ $subcategory->id }}" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                
                                <button type="button" onclick="openDeleteModal({{ $subcategory->id }})"
                                        class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-32 text-center text-slate-400">Aucune sous-catégorie trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($subcategories->hasPages())
    <div class="mt-10 px-4">{{ $subcategories->links() }}</div>
    @endif

</div>

<!-- MODAL DE CONFIRMATION DE SUPPRESSION -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
            <div class="px-8 pt-8 pb-6">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 mx-auto bg-red-100 rounded-2xl dark:bg-red-900/20 sm:mx-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-6 sm:text-left">
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase italic tracking-tighter">Supprimer la sous-catégorie</h3>
                        <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">Cette action est irréversible et supprimera définitivement cette sous-catégorie.</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col-reverse gap-3 px-8 pb-8 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-4 text-sm font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl hover:bg-slate-200 transition-all">Annuler</button>
                <button type="button" onclick="executeDelete()" class="px-8 py-4 text-sm font-black text-white uppercase bg-red-500 rounded-2xl hover:bg-red-600 shadow-lg active:scale-95 transition-all">Confirmer la suppression</button>
            </div>
        </div>
    </div>
</div>

<script>
    let subIdToDelete = null;

    function openDeleteModal(id) {
        subIdToDelete = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        subIdToDelete = null;
    }

    function executeDelete() {
        if (subIdToDelete) {
            const form = document.getElementById('delete-form-' + subIdToDelete);
            if (form) form.submit();
        }
    }
</script>
@endsection