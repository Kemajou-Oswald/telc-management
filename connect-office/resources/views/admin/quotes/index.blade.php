@extends('layouts.admin')

@section('content')
<div class="max-w-[1600px] mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-green font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Commercial / Devis
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Demandes de devis
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 font-medium text-lg">
                Gérez les opportunités commerciales de Connect Office.
            </p>
        </div>
    </div>

    <!-- MINI STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-darkCard p-6 rounded-[2rem] border border-slate-200/50 dark:border-slate-800/50 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14V9a6 6 0 10-12 0v5a2 2 0 01-.6 1.4L4 17h5"/><path d="M9 21h6"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nouveaux</p>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white">{{ $quotes->where('status', 'new')->count() }}</h4>
            </div>
        </div>
        <div class="bg-white dark:bg-darkCard p-6 rounded-[2rem] border border-slate-200/50 dark:border-slate-800/50 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">En cours</p>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white">{{ $quotes->where('status', 'pending')->count() }}</h4>
            </div>
        </div>
        <div class="bg-white dark:bg-darkCard p-6 rounded-[2rem] border border-slate-200/50 dark:border-slate-800/50 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 text-brand-green rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Traités</p>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white">{{ $quotes->where('status', 'completed')->count() }}</h4>
            </div>
        </div>
    </div>

    <!-- TABLEAU DE DEVIS -->
    <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-8 py-6">Client</th>
                        <th class="px-8 py-6">Produit souhaité</th>
                        <th class="px-8 py-6">Date de réception</th>
                        <th class="px-8 py-6 text-center">Statut</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($quotes as $quote)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand-blue to-brand-green flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ strtoupper(substr($quote->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-white leading-tight">{{ $quote->name }}</h3>
                                    <p class="text-xs text-slate-400 tracking-tight">{{ $quote->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-sm font-semibold text-slate-600 dark:text-slate-300">
                            {{ $quote->product->name ?? 'Produit supprimé' }}
                        </td>

                        <td class="px-8 py-6">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $quote->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] font-bold text-brand-blue uppercase tracking-tighter">{{ $quote->created_at->diffForHumans() }}</p>
                        </td>

                        <td class="px-8 py-6 text-center">
                            @if($quote->status === 'new')
                                <span class="px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-brand-blue text-[10px] font-black uppercase tracking-widest border border-brand-blue/20">Nouveau</span>
                            @elseif($quote->status === 'pending')
                                <span class="px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 text-[10px] font-black uppercase tracking-widest border border-amber-200/50">En cours</span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-green-50 dark:bg-green-900/20 text-brand-green text-[10px] font-black uppercase tracking-widest border border-brand-green/20">Traité</span>
                            @endif
                        </td>

                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.quotes.show', $quote->id) }}"
                                   class="p-3 bg-white dark:bg-slate-800 text-brand-blue hover:bg-brand-blue hover:text-white rounded-xl border border-slate-100 dark:border-slate-700 transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                <form action="{{ route('admin.quotes.destroy', $quote->id) }}" method="POST" id="delete-form-{{ $quote->id }}" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                
                                <button type="button" onclick="openDeleteModal({{ $quote->id }})"
                                        class="p-3 bg-white dark:bg-slate-800 text-red-500 hover:bg-red-500 hover:text-white rounded-xl border border-slate-100 dark:border-slate-700 transition shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-32 text-center text-slate-400">Aucune demande de devis reçue.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($quotes->hasPages())
    <div class="mt-10 px-4">{{ $quotes->links() }}</div>
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
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase italic tracking-tighter">Supprimer le devis</h3>
                        <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">Voulez-vous vraiment retirer cette demande de devis ? Cette action est définitive.</p>
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
    let quoteIdToDelete = null;

    function openDeleteModal(id) {
        quoteIdToDelete = id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        quoteIdToDelete = null;
    }

    function executeDelete() {
        if (quoteIdToDelete) {
            const form = document.getElementById('delete-form-' + quoteIdToDelete);
            if (form) form.submit();
        }
    }
</script>
@endsection