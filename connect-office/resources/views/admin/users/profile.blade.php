@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in-up" x-data="profileHandler()">

    <!-- HEADER PREMIUM -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-2 text-brand-green font-bold text-xs uppercase tracking-[0.2em] mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Paramètres / Mon Compte
            </div>
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">
                Mon Profil
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Gérez votre identité et la sécurité de votre accès.</p>
        </div>

        <div class="flex items-center gap-3">
            <button form="profileForm" type="submit"
                    class="px-8 py-4 bg-gradient-to-r from-brand-blue to-brand-green text-white font-black rounded-2xl shadow-xl shadow-brand-blue/20 hover:scale-[1.02] active:scale-95 transition-all">
                Mettre à jour mon profil
            </button>
        </div>
    </div>

    {{-- ALERTES PREMIUM --}}
    @if(session('success'))
        <div class="mb-8 p-5 bg-green-50 dark:bg-green-900/20 border border-brand-green/30 rounded-2xl flex items-center gap-4 text-brand-green animate-slide-in-left">
            <div class="w-10 h-10 bg-brand-green text-white rounded-xl flex items-center justify-center shadow-lg shadow-brand-green/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" 
          id="profileForm"
          method="POST" 
          enctype="multipart/form-data" 
          class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')

        <!-- COLONNE GAUCHE : FORMULAIRE -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Informations Personnelles -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-12 border border-slate-200/50 dark:border-slate-800/50 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 text-brand-blue rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Informations personnelles</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nom Complet</label>
                        <input type="text" name="name" x-model="userName" required
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Adresse Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                               class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-brand-blue/10 focus:border-brand-blue focus:outline-none text-slate-800 dark:text-white font-medium transition-all">
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 md:p-12 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Modification du mot de passe</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Nouveau mot de passe</label>
                        <input type="password" name="password"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="Laissez vide pour conserver">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Confirmation</label>
                        <input type="password" name="password_confirmation"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-red-500/10 focus:border-red-500 focus:outline-none text-slate-800 dark:text-white font-medium transition-all"
                               placeholder="Confirmer le nouveau">
                    </div>
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE : PREVIEW & PHOTO -->
        <div class="space-y-8">
            
            <!-- Carte de Profil Live -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden group text-center">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-blue/20 to-brand-green/20 opacity-50"></div>
                
                <div class="relative z-10">
                    <div class="relative inline-block mb-6">
                        <!-- Preview Image -->
                        <div class="w-32 h-32 rounded-[2.5rem] overflow-hidden border-4 border-white/10 shadow-2xl mx-auto bg-slate-800 flex items-center justify-center">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/'.$user->profile_photo) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl font-black text-white" x-text="userName.charAt(0).toUpperCase()"></span>
                                @endif
                            </template>
                        </div>
                        <!-- Upload Overlay -->
                        <label class="absolute bottom-0 right-0 w-10 h-10 bg-brand-green text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-transform">
                            <input type="file" name="profile_photo" @change="updatePreview" class="sr-only">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                    </div>

                    <h3 class="text-2xl font-black tracking-tight" x-text="userName"></h3>
                    <p class="text-brand-blue font-bold text-xs uppercase tracking-[0.2em] mt-2 italic">
                        {{ $user->role === 'super_admin' ? 'Super Administrateur' : 'Administrateur' }}
                    </p>

                    <div class="mt-8 pt-8 border-t border-white/5 flex justify-center gap-6">
                        <div class="text-center">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Statut</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 bg-brand-green rounded-full animate-pulse"></span>
                                <span class="text-xs font-bold">Actif</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Membre depuis</p>
                            <p class="text-xs font-bold mt-1 text-slate-300">{{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Sécurité -->
            <div class="bg-white dark:bg-darkCard rounded-[2.5rem] p-8 border border-slate-200/50 dark:border-slate-800/50 shadow-sm">
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/20 text-amber-500 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-3">Recommandation</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed italic">
                    "Nous recommandons d'utiliser un mot de passe unique combinant majuscules, chiffres et caractères spéciaux pour garantir la sécurité de l'administration."
                </p>
            </div>

        </div>
    </form>
</div>

<script>
function profileHandler() {
    return {
        userName: '{{ $user->name }}',
        imagePreview: null,
        updatePreview(event) {
            const file = event.target.files[0];
            if (file) {
                this.imagePreview = URL.createObjectURL(file);
            }
        }
    }
}
</script>
@endsection