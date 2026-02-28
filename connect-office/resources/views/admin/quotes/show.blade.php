@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-widest mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Devis / Consultation #{{ $quote->id }}
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-4">
                Détail de la demande
                @if($quote->status === 'new')
                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-brand-blue text-[10px] font-black uppercase rounded-lg border border-brand-blue/20">Nouveau</span>
                @endif
            </h1>
        </div>

        <a href="{{ route('admin.quotes.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la liste
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- COLONNE GAUCHE : LE MESSAGE (2/3) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Le Message -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-10 border border-slate-200/50 dark:border-slate-800/50 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 text-slate-50 dark:text-slate-800/50 pointer-events-none">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9125 16 16.0171 16H19.0171C19.5694 16 20.0171 15.5523 20.0171 15V9C20.0171 8.44772 19.5694 8 19.0171 8H15.0171C14.4648 8 14.0171 7.55228 14.0171 7V4C14.0171 3.44772 14.4648 3 15.0171 3H20.0171C21.1216 3 22.0171 3.89543 22.0171 5V15C22.0171 17.7614 19.7785 20 17.0171 20H15.0171C14.4648 20 14.0171 19.5523 14.0171 19L14.0171 21ZM4.01712 21L4.01712 18C4.01712 16.8954 4.91255 16 6.01712 16H9.01712C9.5694 16 10.0171 15.5523 10.0171 15V9C10.0171 8.44772 9.5694 8 9.01712 8H5.01712C4.46484 8 4.01712 7.55228 4.01712 7V4C4.01712 3.44772 4.46484 3 5.01712 3H10.0171C11.1216 3 12.0171 3.89543 12.0171 5V15C12.0171 17.7614 9.77855 20 7.01712 20H5.01712C4.46484 20 4.01712 19.5523 4.01712 19L4.01712 21Z" /></svg>
                </div>

                <h3 class="text-xs font-black text-brand-blue uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-brand-blue"></span>
                    Contenu du message
                </h3>

                <div class="text-xl leading-relaxed text-slate-700 dark:text-slate-300 font-medium">
                    {{ $quote->message }}
                </div>
            </div>

            <!-- Le Produit -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-brand-blue/20 to-brand-green/10 pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                            <svg class="w-10 h-10 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Produit concerné</p>
                            <h4 class="text-2xl font-black text-white tracking-tight">{{ $quote->product->name ?? 'Produit non spécifié' }}</h4>
                            @if($quote->product)
                                <p class="text-brand-green text-sm font-bold mt-1 tracking-wide">{{ number_format($quote->product->price ?? 0, 0, ',', ' ') }} FCFA</p>
                            @endif
                        </div>
                    </div>

                    @if($quote->product)
                    <a href="{{ route('admin.products.edit', $quote->product->id) }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-black uppercase tracking-widest transition backdrop-blur-md border border-white/10">
                        Voir la fiche produit
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE : CLIENT & STATUS (1/3) -->
        <div class="space-y-8">
            
            <!-- Profil Client -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm overflow-hidden">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-brand-blue to-brand-green rounded-3xl flex items-center justify-center text-white text-3xl font-black mx-auto shadow-lg shadow-brand-blue/30 mb-4">
                        {{ strtoupper(substr($quote->name, 0, 1)) }}
                    </div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white leading-tight">{{ $quote->name }}</h3>
                    <p class="text-brand-blue text-sm font-bold tracking-tight">{{ $quote->company_name ?? 'Client Particulier' }}</p>
                </div>

                <div class="space-y-4 pt-6 border-t border-slate-50 dark:border-slate-800">
                    <div class="flex items-center gap-4 group">
                        <div class="w-10 h-10 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-400 group-hover:text-brand-blue transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest leading-none mb-1">Email</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300 break-all">{{ $quote->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 group">
                        <div class="w-10 h-10 bg-slate-50 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-400 group-hover:text-brand-green transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest leading-none mb-1">Téléphone</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $quote->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestion du Statut -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Action requise
                </h3>

                <form method="POST" action="{{ route('admin.quotes.update', $quote->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="relative">
                        <select name="status" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-sm font-bold text-slate-800 dark:text-white appearance-none focus:ring-4 focus:ring-brand-blue/10 focus:outline-none transition-all cursor-pointer">
                            <option value="new" {{ $quote->status == 'new' ? 'selected' : '' }}>Marquer comme Nouveau</option>
                            <option value="pending" {{ $quote->status == 'pending' ? 'selected' : '' }}>Mettre En cours</option>
                            <option value="processed" {{ $quote->status == 'processed' ? 'selected' : '' }}>Terminer (Traité)</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-slate-800 dark:bg-brand-blue text-white font-black rounded-2xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all">
                        Mettre à jour le statut
                    </button>
                </form>
            </div>

            <!-- Date de réception (Petit info) -->
            <div class="text-center px-4">
                <p class="text-xs text-slate-400 font-medium">Reçu le {{ $quote->created_at->translatedFormat('d F Y à H:i') }}</p>
                <p class="text-[10px] text-brand-green font-bold uppercase tracking-widest mt-1 italic">{{ $quote->created_at->diffForHumans() }}</p>
            </div>

        </div>

    </div>

</div>
@endsection