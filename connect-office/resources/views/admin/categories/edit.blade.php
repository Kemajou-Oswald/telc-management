@extends('layouts.admin')

@section('content')
<!-- Polices Tech -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

<div class="max-w-4xl mx-auto py-10 animate-fade-in-up italic">

    <!-- HEADER AVEC BOUTON RETOUR -->
    <div class="flex items-center justify-between mb-12">
        <div>
            <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                Catalogue / Édition
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Modifier la catégorie
            </h1>
        </div>

        <a href="{{ route('admin.categories.index') }}" 
           class="flex items-center gap-2 text-slate-500 hover:text-brand-blue transition-colors font-bold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/></svg>
            Retour à la liste
        </a>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200/50 dark:border-slate-800/50 shadow-2xl shadow-blue-900/5 overflow-hidden">
        <div class="p-10 lg:p-14">
            
            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="space-y-10">
                @csrf
                @method('PUT')

                <div class="grid gap-10">
                    
                    <!-- CHAMP NOM -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400 ml-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01" /></svg>
                            Nom de la catégorie
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $category->name) }}"
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl px-8 py-5 text-slate-800 dark:text-white font-bold focus:ring-2 focus:ring-brand-blue/30 transition-all placeholder:text-slate-400"
                               placeholder="Ex: Sécurité Électronique"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CHAMP DESCRIPTION -->
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400 ml-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                            Description technique
                        </label>
                        <textarea name="description"
                                  rows="5"
                                  class="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl px-8 py-5 text-slate-800 dark:text-white font-medium focus:ring-2 focus:ring-brand-blue/30 transition-all placeholder:text-slate-400"
                                  placeholder="Décrivez l'univers de cette catégorie...">{{ old('description', $category->description) }}</textarea>
                    </div>

                </div>

                <!-- BOUTON METTRE À JOUR (Signature Connect Office) -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" 
                            class="group relative w-full h-[75px] rounded-2xl font-black text-xs uppercase tracking-[0.4em] overflow-hidden transition-all duration-500 active:scale-95 italic">
                        
                        <!-- Background dégradé animé -->
                        <span class="absolute inset-0 bg-gradient-to-r from-[#1E98C5] via-[#3bb3d6] to-[#9BC53D] animate-gradient-flow group-hover:brightness-110 transition-all duration-700"></span>
                        
                        <!-- Glow effect -->
                        <span class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-[#1E98C5] to-[#9BC53D] blur-xl opacity-20 group-hover:opacity-40 transition-all duration-500"></span>

                        <span class="relative z-10 flex items-center justify-center gap-4 text-white uppercase italic">
                            Confirmer les modifications
                            <svg class="w-6 h-6 transition-transform group-hover:translate-x-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    /* Animation du bouton */
    @keyframes gradient-flow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradient-flow {
        background-size: 200% 200%;
        animation: gradient-flow 4s ease infinite;
    }

    /* Animation d'entrée de la page */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Style inputs focus */
    input:focus, textarea:focus {
        outline: none;
        box-shadow: 0 10px 30px -10px rgba(30, 152, 197, 0.2);
    }
</style>
@endsection