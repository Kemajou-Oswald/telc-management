@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 gap-6">
        <div>
            <div class="flex items-center gap-2 text-brand-green font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Structure du catalogue
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Gestion des catégories
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium">
                Organisez et hiérarchisez vos produits Connect Office.
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
            class="group flex items-center gap-3 bg-gradient-to-r from-brand-blue to-brand-green text-white px-8 py-4 rounded-2xl shadow-lg shadow-brand-blue/20 hover:shadow-brand-green/30 hover:scale-[1.02] active:scale-95 transition-all duration-300">
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="font-bold">Nouvelle catégorie</span>
        </a>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
        <div class="overflow-x-auto px-4 pb-4">
            <table class="w-full">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-8 py-6 text-left">Structure & Hiérarchie</th>
                        <th class="px-8 py-6 text-center">Produits</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($categories as $category)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all duration-300">

                        <!-- Info Catégorie -->
                        <td class="px-8 py-6">
                            <div class="flex items-start gap-4">
                                <div class="mt-1 w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-blue shadow-sm group-hover:border-brand-blue/50 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-white leading-tight">
                                        {{ $category->name }}
                                    </h3>

                                    <!-- Liste des Sous-catégories -->
                                    <div class="mt-4 space-y-2">
                                        @if($category->subcategories->count())
                                        @foreach($category->subcategories as $sub)
                                        <div class="flex items-center gap-3 group/sub">
                                            <div class="w-5 h-5 border-l-2 border-b-2 border-slate-200 dark:border-slate-700 rounded-bl-lg -mt-3"></div>
                                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 py-1 px-3 bg-slate-100 dark:bg-slate-800 rounded-lg hover:text-brand-green transition-colors">
                                                {{ $sub->name }}
                                            </span>
                                            <a href="{{ route('admin.subcategories.edit', $sub->id) }}" class="opacity-0 group-hover/sub:opacity-100 text-brand-blue hover:text-brand-green transition-all transform scale-75">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="flex items-center gap-3 text-slate-400 italic text-[11px] ml-8">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 12H4" />
                                            </svg>
                                            Aucune sous-catégorie
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Nombre de produits (Supposition de relation) -->
                        <td class="px-8 py-6 text-center text-sm font-bold text-slate-400">
                            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                {{ $category->products_count ?? '0' }} produits
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                    class="p-3 bg-amber-50 dark:bg-amber-900/10 text-amber-600 hover:bg-amber-100 dark:hover:bg-amber-900/20 rounded-xl transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 20.414H8v-4.144L18.586 3.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" id="delete-form-{{ $category->id }}" class="hidden">
                                    @csrf @method('DELETE')
                                </form>

                                <button type="button"
                                    onclick="openDeleteModal({{ $category->id }})"
                                    class="p-3 bg-red-50 dark:bg-red-900/10 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/20 rounded-xl transition shadow-sm">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-24 text-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 dark:text-white">Aucune catégorie</h4>
                            <p class="text-slate-500 dark:text-slate-400 mt-1 italic">Commencez par en créer une pour organiser vos produits.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION DESIGN -->
    @if($categories->hasPages())
    <div class="mt-10 px-4">
        {{ $categories->links('pagination::tailwind') }}
    </div>
    @endif

</div>
@endsection
@section('scripts')
<script>
    let categoryIdToDelete = null;

    function openDeleteModal(id) {
        categoryIdToDelete = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        categoryIdToDelete = null;
    }

    document.addEventListener("DOMContentLoaded", function() {
        const confirmBtn = document.getElementById("confirmDeleteBtn");

        if (confirmBtn) {
            confirmBtn.addEventListener("click", function() {
                if (!categoryIdToDelete) return;

                const form = document.getElementById("delete-form-" + categoryIdToDelete);
                if (form) form.submit();
            });
        }
    });
</script>
<!-- MODAL DE CONFIRMATION DE SUPPRESSION -->
<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fond sombre -->
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
                            Supprimer la catégorie
                        </h3>
                        <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">
                            Attention : la suppression d'une catégorie peut impacter les produits rattachés. Cette action est irréversible.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 px-8 pb-8 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-4 text-sm font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl hover:bg-slate-200 transition-all">
                    Annuler
                </button>
                <button type="button" onclick="executeDelete()" class="px-8 py-4 text-sm font-black text-white uppercase bg-red-500 rounded-2xl hover:bg-red-600 shadow-lg active:scale-95 transition-all">
                    Confirmer la suppression
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // On utilise un nom de variable unique pour éviter les conflits
    let idGlobalASupprimer = null;

    function openDeleteModal(id) {
        idGlobalASupprimer = id;
        const modal = document.getElementById('deleteModal');
        if(modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        idGlobalASupprimer = null;
    }

    function executeDelete() {
        if (idGlobalASupprimer) {
            const form = document.getElementById('delete-form-' + idGlobalASupprimer);
            if (form) {
                form.submit();
            }
        }
    }
</script>