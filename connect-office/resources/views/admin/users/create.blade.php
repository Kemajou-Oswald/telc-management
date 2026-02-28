@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM AVEC FIL D'ARIANE -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-widest mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Sécurité / Administrateurs / Nouveau
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Ajouter un membre
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium italic">Configurez un nouvel accès pour l'équipe Connect Office.</p>
        </div>

        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" 
          method="POST" 
          class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf

        <!-- COLONNE GAUCHE : FORMULAIRE (2/3) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Bloc Identification -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-12 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Identification de l'agent</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nom -->
                    <div class="md:col-span-2 lg:col-span-1">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="Ex: Jean Kouassi">
                        @error('name') <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2 lg:col-span-1">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Adresse Email Professionnelle</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="nom@connectoffice.com">
                        @error('email') <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Bloc Sécurité -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-12 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-green-50 dark:bg-green-900/20 text-brand-green rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Sécurité & Accès</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Mot de passe temporaire</label>
                        <input type="password" name="password" required
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="••••••••">
                        @error('password') <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Niveau d'accréditation</label>
                        <div class="relative">
                            <select name="role" required
                                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-bold transition-all appearance-none cursor-pointer">
                                <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Administrateur Standard</option>
                                <option value="super_admin" {{ old('role')=='super_admin'?'selected':'' }}>Super Administrateur</option>
                            </select>
                            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="flex items-center justify-end gap-4">
                <button type="submit" 
                        class="group flex items-center gap-3 px-12 py-5 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-xl shadow-brand-blue/20 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                    <span>Finaliser la création</span>
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>
        </div>

        <!-- COLONNE DROITE : INFO & TIPS (1/3) -->
        <div class="space-y-6">
            
            <!-- Carte Rôles (Design Noir) -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-brand-blue/10 rounded-full blur-2xl group-hover:bg-brand-green/20 transition-all duration-700"></div>
                
                <h3 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Guide des rôles
                </h3>

                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-xs font-black text-brand-blue uppercase tracking-widest mb-1">Super Admin</p>
                        <p class="text-[11px] text-slate-400 leading-relaxed font-medium">Contrôle total sur les produits, les devis, et peut gérer les autres administrateurs.</p>
                    </div>
                    <div class="pt-4 border-t border-white/5">
                        <p class="text-xs font-black text-brand-green uppercase tracking-widest mb-1">Admin Standard</p>
                        <p class="text-[11px] text-slate-400 leading-relaxed font-medium">Peut gérer le catalogue et les devis, mais n'a pas accès à la gestion de l'équipe.</p>
                    </div>
                </div>
            </div>

            <!-- Conseil Sécurité -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-3">Sécurité des mots de passe</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed italic">
                    "Le nouvel utilisateur devra modifier ce mot de passe dès sa première connexion pour garantir la sécurité de son accès personnel."
                </p>
            </div>

        </div>

    </form>
</div>
@endsection