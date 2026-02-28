@php
    // Helper pour les classes actives
    $active = 'bg-white/10 text-white shadow-lg shadow-black/20 border-r-4 border-brandRed';
    $inactive = 'text-white/60 hover:text-white hover:bg-white/5 transition-all duration-200';
    $itemBase = 'flex items-center gap-3.5 px-4 py-3 rounded-xl group';
@endphp

<div class="space-y-7">
    
    <!-- SECTION : TABLEAU DE BORD -->
    <div>
        <h3 class="px-4 text-[10px] font-bold text-white/30 uppercase tracking-[2px] mb-3">Pilotage</h3>
        <a href="{{ route('dashboard') }}" class="{{ $itemBase }} {{ (request()->routeIs('dashboard') || request()->routeIs('dashboards.*')) ? $active : $inactive }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-sm font-semibold">Vue d'ensemble</span>
        </a>
    </div>

    <!-- SECTION : ACADÉMIQUE (Directeur, Secrétaire, Admin) -->
    @if(auth()->user()->role !== 'professeur')
    <div>
        <h3 class="px-4 text-[10px] font-bold text-white/30 uppercase tracking-[2px] mb-3">Scolarité</h3>
        <div class="space-y-1">
            <a href="{{ route('students.index') }}" class="{{ $itemBase }} {{ request()->routeIs('students.*') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="text-sm font-semibold">Étudiants</span>
            </a>
            <a href="{{ route('groups.index') }}" class="{{ $itemBase }} {{ request()->routeIs('groups.*') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <span class="text-sm font-semibold">Classes / Vagues</span>
            </a>
        </div>
    </div>
    @endif

    <!-- SECTION : PRÉSENCES -->
    <div>
        <h3 class="px-4 text-[10px] font-bold text-white/30 uppercase tracking-[2px] mb-3">Présences</h3>
        <div class="space-y-1">
            {{-- Vue Secrétaire : Valider la présence des profs --}}
            @if(auth()->user()->role == 'secretaire')
            <a href="{{ route('teacher.validation') }}" class="{{ $itemBase }} {{ request()->routeIs('teacher.validation') ? $active : $inactive }}">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="text-sm font-semibold italic">Validation Profs</span>
            </a>
            @endif

            {{-- Vue Professeur : Faire l'appel --}}
            @if(auth()->user()->role == 'professeur')
            <p class="px-4 text-[9px] text-white/40 italic">Sélectionnez une classe pour l'appel</p>
            {{-- Ici on pourrait boucler sur les groupes du prof, ou mettre un lien vers une liste --}}
            <a href="{{ route('student_attendances.index') }}" class="{{ $itemBase }} {{ request()->routeIs('student_attendances.*') ? $active : $inactive }}">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                <span class="text-sm font-semibold">Faire l'appel</span>
            </a>
            @endif

            {{-- Vue Directeur / Super Admin : stats profs --}}
            @if(in_array(auth()->user()->role, ['super_admin', 'directeur']))
            <a href="{{ route('teacher.stats') }}" class="{{ $itemBase }} {{ request()->routeIs('teacher.stats') ? $active : $inactive }}">
                <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m6 0h4m-4 0a2 2 0 11-4 0m8 0v-4a2 2 0 012-2h2a2 2 0 012 2v4m-6 0a2 2 0 104 0"/>
                </svg>
                <span class="text-sm font-semibold">Suivi Profs</span>
            </a>
            @endif
        </div>
    </div>

    <!-- SECTION : SUPPORT (Secrétaire, Directeur, Super Admin) -->
    @if(in_array(auth()->user()->role, ['super_admin', 'directeur', 'secretaire']))
    <div>
        <h3 class="px-4 text-[10px] font-bold text-white/30 uppercase tracking-[2px] mb-3">Support</h3>
        <div class="space-y-1">
            <a href="{{ route('support_requests.index') }}" class="{{ $itemBase }} {{ request()->routeIs('support_requests.*') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636A9 9 0 105.636 18.364 9 9 0 0018.364 5.636zM12 8v4m0 4h.01"/>
                </svg>
                <span class="text-sm font-semibold">Réclamations</span>
            </a>
        </div>
    </div>
    @endif

    <!-- SECTION : FINANCES (Super Admin / Directeur / Secrétaire) -->
    @if(in_array(auth()->user()->role, ['super_admin', 'directeur', 'secretaire']))
    <div>
        <h3 class="px-4 text-[10px] font-bold text-white/30 uppercase tracking-[2px] mb-3">Finances & Stock</h3>
        <div class="space-y-1">
            <a href="{{ route('cash.index') }}" class="{{ $itemBase }} {{ request()->routeIs('cash.*') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M5 10h14a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm3 3h.01M7 5a2 2 0 012-2h6a2 2 0 012 2v2H7V5z"></path>
                </svg>
                <span class="text-sm font-semibold">Caisse journalière</span>
            </a>

            @if(auth()->user()->role === 'super_admin')
            <a href="{{ route('tuition_fees.index') }}" class="{{ $itemBase }} {{ request()->routeIs('tuition_fees.*') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-sm font-semibold">Gestion cours</span>
            </a>
            @endif

            <a href="{{ route('stock.index') }}" class="{{ $itemBase }} {{ request()->routeIs('stock.*') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M5 10h14v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7z"/>
                </svg>
                <span class="text-sm font-semibold">Stock</span>
            </a>
        </div>
    </div>
    @endif

    <!-- SECTION : ADMINISTRATION (Super Admin / Directeur uniquement) -->
    @if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'directeur')
    <div>
        <h3 class="px-4 text-[10px] font-bold text-white/30 uppercase tracking-[2px] mb-3">Config & RH</h3>
        <div class="space-y-1">
            <a href="{{ route('users.index') }}" class="{{ $itemBase }} {{ request()->routeIs('users.index') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="text-sm font-semibold">Personnel</span>
            </a>
            <a href="{{ route('users.create') }}" class="{{ $itemBase }} {{ request()->routeIs('users.create') ? $active : $inactive }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                <span class="text-sm font-semibold">Ajouter Personnel</span>
            </a>
        </div>
    </div>
    @endif

</div>