@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM AVEC FIL D'ARIANE -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Inventaire / Sous-catégories / Nouvelle
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Créer une sous-catégorie
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Affinez la classification de vos produits Connect Office.</p>
        </div>

        <a href="{{ route('admin.subcategories.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour
        </a>
    </div>

    <!-- MAIN CONTENT CARD -->
    <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
        
        <div class="flex flex-col lg:flex-row">
            
            <!-- COLONNE FORMULAIRE (GAUCHE) -->
            <div class="flex-1 p-8 md:p-12 border-r border-slate-100 dark:border-slate-800">
                <form action="{{ route('admin.subcategories.store') }}" method="POST" class="space-y-10">
                    @csrf

                    <!-- Section Identification -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Détails de l'élément</h2>
                        </div>

                        <div class="grid gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom de la sous-catégorie</label>
                                <input type="text" name="name" required
                                       class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                                       placeholder="Ex: Fauteuils Ergonomiques">
                                @error('name')
                                    <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Catégorie Parente</label>
                                <div class="relative">
                                    <select name="category_id" required
                                            class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all appearance-none cursor-pointer">
                                        <option value="">Sélectionner la catégorie parente</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                @error('category_id')
                                    <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions Boutons -->
                    <div class="pt-6 border-t border-slate-50 dark:border-slate-800 flex items-center justify-end gap-4">
                        <button type="submit" 
                                class="group flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-lg shadow-brand-blue/20 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                            <span>Créer la sous-catégorie</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- PANNEAU D'AIDE (DROITE) -->
            <div class="w-full lg:w-80 bg-slate-50/50 dark:bg-slate-900/30 p-10">
                <div class="sticky top-10 space-y-10">
                    
                    <!-- Conseil 1 -->
                    <div class="space-y-4">
                        <div class="w-12 h-12 bg-white dark:bg-darkCard rounded-2xl shadow-sm flex items-center justify-center text-brand-green">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider leading-tight">Pourquoi une sous-catégorie ?</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium italic">
                            "Elles permettent à vos clients de trouver plus facilement des produits spécifiques (ex: Fauteuils) au sein d'un univers plus large (ex: Mobilier)."
                        </p>
                    </div>

                    <!-- Illustration Factice Hiérarchie -->
                    <div class="p-6 bg-white dark:bg-darkCard rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                        <h4 class="text-[10px] font-black text-brand-blue uppercase tracking-widest mb-4">Aperçu de la structure</h4>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-brand-green"></div>
                                <div class="h-4 w-24 bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <div class="w-4 h-4 border-l-2 border-b-2 border-slate-200 dark:border-slate-700 rounded-bl-md"></div>
                                <div class="h-3 w-20 bg-brand-blue/20 rounded-full"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection