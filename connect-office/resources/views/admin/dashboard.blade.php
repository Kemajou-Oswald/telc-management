@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in-up">

    <!-- SECTION 1 : WELCOME BANNER -->
    <div class="relative overflow-hidden bg-gradient-to-r from-brand-blue to-brand-green rounded-[2.5rem] p-10 text-white shadow-lg">
        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold mb-2">Tableau de Bord</h2>
            <p class="text-white/80 max-w-md font-medium">
                Ravi de vous revoir ! Voici ce qui s'est passé sur Connect Office aujourd'hui.
            </p>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl text-sm font-bold transition">
                    Gérer le catalogue
                </a>
                <a href="{{ route('admin.quotes.index') }}" class="px-5 py-2.5 bg-white text-brand-blue rounded-xl text-sm font-bold shadow-sm transition hover:scale-105">
                    Voir les devis
                </a>
            </div>
        </div>
        <!-- Décoration abstraite en fond -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-20 -mb-10 w-32 h-32 bg-brand-green/20 rounded-full blur-2xl"></div>
    </div>

    <!-- SECTION 2 : STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Produits -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Total Produits</p>
                    <h3 class="text-3xl font-black mt-2 text-slate-800 dark:text-white">{{ $totalProducts }}</h3>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-semibold text-brand-green">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                <span>Catalogue actif</span>
            </div>
        </div>

        <!-- Catégories -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Catégories</p>
                    <h3 class="text-3xl font-black mt-2 text-slate-800 dark:text-white">{{ $totalCategories }}</h3>
                </div>
                <div class="p-3 bg-green-50 dark:bg-green-900/20 text-brand-green rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                <span>Structuration optimisée</span>
            </div>
        </div>

        <!-- Devis Total -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">Total Devis</p>
                    <h3 class="text-3xl font-black mt-2 text-slate-800 dark:text-white">{{ $totalQuotes }}</h3>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-semibold text-slate-400">
                <span>Demandes reçues</span>
            </div>
        </div>

        <!-- Nouveaux Devis -->
        <div class="bg-white dark:bg-darkCard p-6 rounded-[2rem] border border-brand-green/30 shadow-lg shadow-brand-green/5 transition relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-brand-green text-xs font-bold uppercase tracking-wider text-green-600 dark:text-brand-green">À Traiter</p>
                    <h3 class="text-3xl font-black mt-2 text-slate-800 dark:text-white">{{ $newQuotes }}</h3>
                </div>
                <div class="p-3 bg-brand-green text-white rounded-2xl shadow-lg shadow-brand-green/30 animate-pulse">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14V9a6 6 0 10-12 0v5a2 2 0 01-.6 1.4L4 17h5"/><path d="M9 21h6"/></svg>
                </div>
            </div>
            <!-- Badge "Action requise" -->
            <div class="mt-4 inline-flex items-center px-2.5 py-1 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-brand-green text-[10px] font-bold uppercase">
                Action immédiate requise
            </div>
        </div>
    </div>

    <!-- SECTION 3 : DEUX COLONNES (DEVIS & ACTIONS) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Derniers Devis (2/3) -->
        <div class="lg:col-span-2 bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                    <span class="w-2 h-6 bg-brand-blue rounded-full"></span>
                    Dernières demandes de devis
                </h3>
                <a href="{{ route('admin.quotes.index') }}" class="text-sm font-bold text-brand-blue hover:underline">Voir tout</a>
            </div>
            <div class="overflow-x-auto px-4 pb-4">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                            <th class="px-6 py-4 font-bold uppercase">Client</th>
                            <th class="px-6 py-4 font-bold uppercase">Date</th>
                            <th class="px-6 py-4 font-bold uppercase">Statut</th>
                            <th class="px-6 py-4 font-bold uppercase text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        {{-- On suppose que tu as une variable $latestQuotes venant du controller --}}
                        @forelse($latestQuotes ?? [] as $quote)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $quote->client_name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $quote->client_email }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $quote->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $quote->status == 'pending' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600' }}">
                                    {{ $quote->status == 'pending' ? 'En attente' : 'Traité' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.quotes.show', $quote) }}" class="p-2 inline-flex bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-brand-blue hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Aucune demande récente.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ACTIONS RAPIDES (1/3) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Actions Rapides</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center justify-center p-4 rounded-3xl bg-slate-50 dark:bg-slate-800 hover:bg-brand-blue group transition duration-300">
                        <div class="w-12 h-12 mb-3 bg-white dark:bg-slate-700 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 group-hover:text-white uppercase">Produit</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex flex-col items-center justify-center p-4 rounded-3xl bg-slate-50 dark:bg-slate-800 hover:bg-brand-green group transition duration-300">
                        <div class="w-12 h-12 mb-3 bg-white dark:bg-slate-700 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h10M7 12h10M7 17h10"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 group-hover:text-white uppercase">Catégorie</span>
                    </a>
                </div>
            </div>

            <!-- SYSTEM STATUS -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl">
                <h3 class="text-lg font-bold mb-4">État du Système</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400">Serveur</span>
                        <span class="text-brand-green font-bold">Opérationnel</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-1.5">
                        <div class="bg-brand-green h-1.5 rounded-full w-[98%] shadow-[0_0_10px_rgba(125,187,66,0.5)]"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 italic">Mise à jour : {{ now()->format('H:i') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection