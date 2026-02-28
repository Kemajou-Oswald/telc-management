<!DOCTYPE html>
<html lang="fr" 
    x-data="{ 
        sidebar: true, 
        dark: localStorage.getItem('dark') === 'true',
        catOpen: {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') ? 'true' : 'false' }},
        toggleDark() {
            this.dark = !this.dark;
            localStorage.setItem('dark', this.dark);
        }
    }" 
    :class="{ 'dark': dark }">

<head>
    <meta charset="UTF-8">
    <title>Connect Office | Administration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { 
                            blue: '#1E98C5',
                            green: '#7DBB42',
                            darkBlue: '#157da5',
                            darkGreen: '#669a35'
                        },
                        darkBg: '#0f172a',
                        darkCard: '#1e293b'
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Menu Actif - Dégradé Bleu/Vert */
        .nav-link-active {
            background: linear-gradient(90deg, rgba(30, 152, 197, 0.1) 0%, rgba(125, 187, 66, 0.1) 100%);
            color: #1E98C5 !important;
            border-left: 4px solid #7DBB42;
        }

        .dark .nav-link-active {
            background: linear-gradient(90deg, rgba(30, 152, 197, 0.15) 0%, rgba(125, 187, 66, 0.15) 100%);
        }

        /* Barre de défilement fine */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }

        .sidebar-transition { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>

<body class="bg-[#F4F7F9] dark:bg-darkBg text-slate-900 dark:text-slate-200 antialiased overflow-hidden">

    <div class="flex h-screen p-0 md:p-4 gap-4 font-sans">

        <!-- SIDEBAR -->
        <aside 
            :class="sidebar ? 'w-72' : 'w-24'"
            class="sidebar-transition hidden md:flex flex-col bg-white dark:bg-darkCard rounded-[2rem] shadow-xl shadow-blue-900/5 dark:shadow-none border border-slate-200/50 dark:border-slate-800/50 overflow-hidden">
            
            <!-- Logo Section -->
            <div class="p-8 flex items-center justify-center min-h-[110px]">
                <img x-show="sidebar" x-transition.opacity src="{{ asset('images/logo-connect-office.png') }}" class="h-10 w-auto">
                <div x-show="!sidebar" class="w-12 h-12 bg-gradient-to-br from-brand-blue to-brand-green rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">C</div>
            </div>

            <!-- Navigation Scrollable -->
            <nav class="flex-1 px-4 space-y-2 overflow-y-auto custom-scrollbar">
                
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span x-show="sidebar" class="font-semibold whitespace-nowrap">Tableau de bord</span>
                </a>

                <!-- Produits -->
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'nav-link-active' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span x-show="sidebar" class="font-semibold whitespace-nowrap">Produits</span>
                </a>

                <!-- Dropdown Catégories & Sous-catégories -->
                <div class="space-y-1">
                    <button @click="catOpen = !catOpen"
                            class="w-full flex items-center justify-between gap-4 px-4 py-3.5 rounded-2xl text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span x-show="sidebar" class="font-semibold whitespace-nowrap">Inventaire</span>
                        </div>
                        <svg x-show="sidebar" :class="catOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="catOpen && sidebar" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" class="pl-12 pr-2 space-y-1">
                        <a href="{{ route('admin.categories.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.categories.*') ? 'text-brand-blue font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-brand-blue' }}">Catégories</a>
                        <a href="{{ route('admin.subcategories.index') }}" class="block py-2 text-sm {{ request()->routeIs('admin.subcategories.*') ? 'text-brand-blue font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-brand-blue' }}">Sous-catégories</a>
                    </div>
                </div>

                <!-- Devis -->
                <a href="{{ route('admin.quotes.index') }}"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.quotes.*') ? 'nav-link-active' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span x-show="sidebar" class="font-semibold">Devis</span>
                </a>

                <!-- Admins -->
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span x-show="sidebar" class="font-semibold">Équipe</span>
                </a>

            </nav>

            <!-- Logout Section -->
            <div class="p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center md:justify-start gap-4 px-4 py-4 rounded-[1.5rem] bg-slate-50 dark:bg-slate-800/50 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all duration-300 group">
                        <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span x-show="sidebar" class="font-bold">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- HEADER -->
            <header class="bg-white/70 dark:bg-darkCard/70 backdrop-blur-md sticky top-0 z-40 mb-4 flex items-center justify-between px-8 py-4 rounded-[2rem] border border-white/20 shadow-sm border border-slate-200/50 dark:border-slate-800/50">
                <div class="flex items-center gap-6">
                    <button @click="sidebar = !sidebar" class="p-2.5 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:border-brand-blue transition-all">
                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Espace Connect</h1>
                        <p class="text-[10px] font-bold text-brand-green uppercase tracking-[0.2em]">Management System</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dark Mode -->
                    <button @click="toggleDark()" class="p-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all">
                        <svg x-show="!dark" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                    </button>

                    <!-- Profil Admin Dynamique -->
                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 p-1.5 pr-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all">
                        <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-brand-green shadow-inner">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-brand-blue to-brand-green flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[9px] text-brand-blue font-bold uppercase tracking-tighter">Administrateur</p>
                        </div>
                    </a>
                </div>
            </header>

            <!-- ZONE DE CONTENU -->
            <main class="flex-1 overflow-y-auto px-4 custom-scrollbar pb-8">
                <div class="animate-fade-in-up">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

</body>
</html>