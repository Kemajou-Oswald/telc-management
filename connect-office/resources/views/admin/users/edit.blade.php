@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-6">
            <!-- Avatar Preview -->
            <div class="relative">
                <div class="w-20 h-20 rounded-[2rem] overflow-hidden border-4 border-white dark:border-slate-800 shadow-xl shadow-brand-blue/10">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/'.$user->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-brand-blue to-brand-green flex items-center justify-center text-white text-2xl font-black">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                    @endif
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 {{ $user->is_active ? 'bg-brand-green' : 'bg-red-500' }} border-4 border-[#F4F7F9] dark:border-darkBg rounded-full shadow-sm"></div>
            </div>

            <div>
                <div class="flex items-center gap-2 text-brand-blue font-bold text-xs uppercase tracking-widest mb-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 20.414H8v-4.144L18.586 3.586z"/></svg>
                    Édition de compte
                </div>
                <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                    {{ $user->name }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Modifiez les privilèges et les accès de cet agent.</p>
            </div>
        </div>

        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-darkCard border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour
        </a>
    </div>

    <!-- ERREURS DE VALIDATION -->
    @if ($errors->any())
    <div class="mb-8 p-6 rounded-[2rem] bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20 animate-pulse">
        <div class="flex items-center gap-3 text-red-600 dark:text-red-400 font-bold mb-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Erreur de saisie
        </div>
        <ul class="text-sm text-red-500/80 space-y-1 ml-8 list-disc font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')

        <!-- COLONNE GAUCHE (2/3) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Informations Générales -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-12 border border-slate-200/50 dark:border-slate-800/50 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-10 text-brand-blue/5 pointer-events-none">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>

                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3 mb-10">
                    <span class="w-1.5 h-6 bg-brand-blue rounded-full"></span>
                    Profil administrateur
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom Complet</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email Professionnel</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all">
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-12 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3 mb-10">
                    <span class="w-1.5 h-6 bg-brand-green rounded-full"></span>
                    Sécurité & Authentification
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Changer le mot de passe</label>
                        <input type="password" name="password"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="Laisser vide pour inchangé">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Confirmation</label>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-green/10 focus:border-brand-green focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="Confirmer le nouveau">
                    </div>
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE (1/3) -->
        <div class="space-y-8">
            
            <!-- Super Admin Controls -->
            @if(auth()->user()->isSuperAdmin())
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-8 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Privilèges Système
                </h3>

                <div class="space-y-8">
                    <!-- Rôle -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">Rôle Assigné</label>
                        <div class="relative">
                            <select name="role" class="w-full bg-slate-800 border border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-white appearance-none focus:ring-2 focus:ring-brand-blue focus:outline-none transition-all cursor-pointer">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur Standard</option>
                                <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Administrateur</option>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Toggle Actif -->
                    <div class="pt-6 border-t border-slate-800">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div>
                                <p class="text-sm font-bold group-hover:text-brand-green transition-colors">Compte Actif</p>
                                <p class="text-[10px] text-slate-500">Autoriser l'accès à la plateforme</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-green"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            @endif

            <!-- Security Warning -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-3">Conseil de sécurité</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed italic">
                    "Ne modifiez le mot de passe d'un agent que sur sa demande expresse ou en cas de faille de sécurité suspectée."
                </p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-3 px-8 py-5 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-xl shadow-brand-blue/20 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                <span>Enregistrer</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection