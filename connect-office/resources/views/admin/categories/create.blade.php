@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto animate-fade-in-up">

    <!-- HEADER AVEC FIL D'ARIANE -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-widest mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Inventaire / Catégories
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Créer une catégorie
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">
                Définissez un nouvel espace pour vos produits Connect Office.
            </p>
        </div>

        <a href="{{ route('admin.categories.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la liste
        </a>
    </div>

    <!-- MAIN FORM CARD -->
    <div class="bg-white dark:bg-darkCard rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-xl shadow-blue-900/5 overflow-hidden">
        
        <form action="{{ route('admin.categories.store') }}"
              method="POST"
              x-data="{
                name: '{{ old('name') }}',
                get slug() {
                    return this.name.toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Enlever accents
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/--+/g, '-')
                        .trim();
                }
              }"
              class="flex flex-col lg:flex-row">
            @csrf

            <!-- CÔTÉ GAUCHE : FORMULAIRE -->
            <div class="flex-1 p-8 md:p-12 space-y-10">
                
                <!-- Section Nom -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <label class="text-lg font-bold text-slate-700 dark:text-slate-200">Identification</label>
                    </div>

                    <div class="grid gap-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom de la catégorie</label>
                            <input type="text"
                                   name="name"
                                   x-model="name"
                                   class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all placeholder:text-slate-400"
                                   placeholder="Ex: Mobilier de bureau ergonomique"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Lien permanent (Slug)</label>
                            <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-900/50 px-6 py-4 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span class="text-sm font-mono text-brand-blue dark:text-brand-green truncate" x-text="slug || 'votre-slug-automatique'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Description -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-50 dark:bg-green-900/20 text-brand-green rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </div>
                        <label class="text-lg font-bold text-slate-700 dark:text-slate-200">Détails de l'espace</label>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Description marketing</label>
                        <textarea name="description"
                                  rows="5"
                                  class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all resize-none placeholder:text-slate-400"
                                  placeholder="Expliquez ce que contient cette catégorie pour vos clients...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="submit"
                            class="group relative flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-lg shadow-brand-blue/20 hover:shadow-brand-green/40 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        <span>Enregistrer la catégorie</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            <!-- CÔTÉ DROIT : PREVIEW/HELPER -->
            <div class="w-full lg:w-80 bg-slate-50 dark:bg-slate-900/30 p-8 border-l border-slate-100 dark:border-slate-800">
                <div class="sticky top-10 space-y-8">
                    <div class="p-6 bg-white dark:bg-darkCard rounded-[2rem] shadow-sm border border-slate-200/50 dark:border-slate-800/50">
                        <h4 class="text-xs font-black text-brand-blue uppercase tracking-widest mb-4">Aperçu direct</h4>
                        <div class="space-y-3">
                            <div class="w-12 h-1 bg-brand-green rounded-full"></div>
                            <h5 class="text-xl font-bold text-slate-800 dark:text-white break-words" x-text="name || 'Nom ici'"></h5>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-3 italic">"La description apparaîtra ici sur le site public."</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest px-2">Conseils Connect</h4>
                        <ul class="space-y-3">
                            <li class="flex gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium leading-tight">
                                <svg class="w-4 h-4 text-brand-green shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                Utilisez des noms clairs pour le référencement (SEO).
                            </li>
                            <li class="flex gap-3 text-xs text-slate-500 dark:text-slate-400 font-medium leading-tight">
                                <svg class="w-4 h-4 text-brand-green shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                Le slug se met à jour en temps réel pour vous faire gagner du temps.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection