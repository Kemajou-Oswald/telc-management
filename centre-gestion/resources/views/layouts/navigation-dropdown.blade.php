<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
        <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-[#1D1B84] font-bold border border-slate-200 group-hover:bg-[#1D1B84] group-hover:text-white transition-all">
            {{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'A' }}
        </div>
        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Menu Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50" 
         x-cloak>
        
        <div class="px-4 py-2 border-b border-slate-50">
            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Compte</p>
            <p class="text-sm font-medium text-slate-700 truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
        </div>

        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Mon Profil</a>

        <div class="border-t border-slate-50 my-1"></div>

        {{-- Formulaire de déconnexion sécurisé --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                Déconnexion
            </button>
        </form>
    </div>
</div>