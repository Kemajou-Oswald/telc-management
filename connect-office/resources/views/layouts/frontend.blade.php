<!DOCTYPE html>
<html lang="fr" class="dark scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Connect Office — Infrastructures & Sécurité')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connect Office : Expert en sécurité électronique, réseaux, énergies renouvelables et GTB au Cameroun.">

    <!-- Fonts: Plus Jakarta Sans (La police Tech par excellence) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#3bb3d6',
                            500: '#1E98C5',
                            600: '#167ca3',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        accent: '#9BC53D', // Vert Connect Office
                        dark: '#010409'   // Noir Profond GitHub style
                    },
                    animation: {
                        'shimmer': 'shimmer 2s linear infinite',
                    },
                    keyframes: {
                        shimmer: {
                            '0%': { backgroundPosition: '200% 0' },
                            '100%': { backgroundPosition: '-200% 0' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Texture de grain globale */
        .bg-grain {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }
        
        /* Scrollbar personnalisée */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #010409; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#010409] text-slate-200 font-sans antialiased overflow-x-hidden selection:bg-brand-500/30 selection:text-white">

    <!-- Texture de fond persistante sur toutes les pages -->
    <div class="fixed inset-0 z-[0] opacity-[0.04] pointer-events-none bg-grain mix-blend-overlay"></div>

    <!-- ================= HEADER ================= -->
    <header x-data="{ mobileMenuOpen: false, scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="{ 'bg-[#010409]/80 backdrop-blur-xl border-white/10 shadow-lg shadow-black/50': scrolled, 'bg-transparent border-transparent': !scrolled }"
            class="fixed top-0 w-full z-50 transition-all duration-300 border-b">

        <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between transition-all duration-300" 
             :class="scrolled ? 'h-20' : 'h-24'">

            <!-- LOGO RESTAURÉ -->
            <a href="{{ route('home') }}" class="relative group z-50 block">
                <img src="{{ asset('images/logo-connect-office.png') }}" 
                     alt="Connect Office" 
                     class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105 group-hover:brightness-110">
            </a>

            <!-- Navigation Desktop avec Overlay Hover -->
            <nav class="hidden lg:flex items-center gap-1 p-1 rounded-full bg-white/0 border border-transparent">
                @php
                    $navItems = [
                        ['label' => 'Accueil', 'route' => 'home'],
                        ['label' => 'Qui sommes-nous', 'route' => 'about'],
                        ['label' => 'Solutions', 'route' => 'solutions'],
                        ['label' => 'Catalogue', 'route' => 'catalogue'],
                        ['label' => 'Contact', 'route' => 'contact'],
                    ];
                @endphp

                @foreach($navItems as $item)
                @php $isActive = Route::currentRouteName() == $item['route']; @endphp
                
                <a href="{{ route($item['route']) }}"
                   class="relative px-5 py-2 rounded-full group transition-all duration-300 ease-out">
                    
                    <!-- Fond Actif (Fixe) -->
                    @if($isActive)
                        <div class="absolute inset-0 bg-white/10 rounded-full border border-white/5 shadow-inner"></div>
                    @endif

                    <!-- Overlay au Survol (Animation) -->
                    <div class="absolute inset-0 bg-white/5 rounded-full opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300"></div>

                    <!-- Texte du lien -->
                    <span class="relative z-10 text-sm font-medium transition-colors duration-300 {{ $isActive ? 'text-white' : 'text-slate-300 group-hover:text-white' }}">
                        {{ $item['label'] }}
                    </span>
                </a>
                @endforeach
            </nav>

            <!-- CTA Desktop -->
            <div class="hidden lg:block">
                <a href="{{ route('contact') }}"
                   class="group relative inline-flex items-center gap-2 px-6 py-3 bg-brand-600 text-white rounded-xl font-bold text-sm overflow-hidden transition-all hover:scale-105 hover:shadow-[0_0_30px_rgba(30,152,197,0.3)] border border-white/10">
                    
                    <!-- Shine Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                    
                    <span class="relative z-10">Demander un devis</span>
                    <svg class="w-4 h-4 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

            <!-- Hamburger Mobile -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-white p-2 z-50 hover:bg-white/10 rounded-lg transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

        </div>

        <!-- Menu Mobile Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak
             class="absolute top-[80px] left-0 w-full bg-[#010409]/95 backdrop-blur-xl border-b border-white/10 lg:hidden shadow-2xl">
            <div class="p-6 space-y-2">
                @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" 
                   class="block px-4 py-3 rounded-lg text-lg font-medium text-slate-300 hover:text-white hover:bg-white/5 transition-colors {{ Route::currentRouteName() == $item['route'] ? 'bg-white/5 text-white' : '' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
                <div class="pt-4 mt-4 border-t border-white/10">
                    <a href="{{ route('contact') }}" class="block w-full text-center px-6 py-4 bg-brand-600 text-white rounded-xl font-bold shadow-lg">
                        Demander un devis
                    </a>
                </div>
            </div>
        </div>

    </header>

    <!-- ================= PAGE CONTENT ================= -->
    <main class="relative z-10 min-h-screen">
        @yield('content')
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="relative z-10 border-t border-white/5 bg-[#010409] pt-24 pb-12 overflow-hidden">
        
        <!-- Glow Footer -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-5xl h-[1px] bg-gradient-to-r from-transparent via-brand-500/30 to-transparent"></div>
        <div class="absolute bottom-[-100px] right-[-100px] w-[600px] h-[600px] bg-brand-900/10 blur-[150px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">

                <!-- Brand -->
                <div class="space-y-6">
                    <!-- LOGO RESTAURÉ FOOTER -->
                    <a href="{{ route('home') }}" class="block">
                        <img src="{{ asset('images/logo-connect-office.png') }}" 
                             alt="Connect Office" 
                             class="h-10 w-auto opacity-90 hover:opacity-100 transition-opacity">
                    </a>
                    
                    <p class="text-slate-400 leading-relaxed text-sm pr-4 font-light">
                        Leader au Cameroun dans la conception d'infrastructures technologiques intelligentes, sécurisées et durables pour les entreprises exigeantes.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-brand-500 hover:border-brand-500 transition-all text-slate-400 hover:text-white group">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#0077b5] hover:border-[#0077b5] transition-all text-slate-400 hover:text-white group">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Expertise -->
                <div>
                    <h3 class="font-bold text-white mb-6 uppercase tracking-widest text-[10px] text-brand-500">Expertise</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="{{ route('solutions') }}" class="hover:text-white transition-colors flex items-center gap-3 group"><span class="w-1.5 h-1.5 bg-slate-600 rounded-full group-hover:bg-brand-500 transition-colors"></span> Sécurité électronique</a></li>
                        <li><a href="{{ route('solutions') }}" class="hover:text-white transition-colors flex items-center gap-3 group"><span class="w-1.5 h-1.5 bg-slate-600 rounded-full group-hover:bg-brand-500 transition-colors"></span> Réseaux & Télécom</a></li>
                        <li><a href="{{ route('solutions') }}" class="hover:text-white transition-colors flex items-center gap-3 group"><span class="w-1.5 h-1.5 bg-slate-600 rounded-full group-hover:bg-[#9BC53D] transition-colors"></span> Énergies solaires</a></li>
                        <li><a href="{{ route('solutions') }}" class="hover:text-white transition-colors flex items-center gap-3 group"><span class="w-1.5 h-1.5 bg-slate-600 rounded-full group-hover:bg-brand-500 transition-colors"></span> GTB & Domotique</a></li>
                    </ul>
                </div>

                <!-- Navigation -->
                <div>
                    <h3 class="font-bold text-white mb-6 uppercase tracking-widest text-[10px] text-brand-500">Connect Office</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">L'Entreprise</a></li>
                        <li><a href="{{ route('catalogue') }}" class="hover:text-white transition-colors">Notre Catalogue</a></li>
                        <li><a href="{{ route('home') }}#realisations" class="hover:text-white transition-colors">Nos Réalisations</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Demander un devis</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="font-bold text-white mb-6 uppercase tracking-widest text-[10px] text-brand-500">Nous Contacter</h3>
                    <ul class="space-y-5 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <div class="mt-1 w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center shrink-0 text-brand-500">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <span class="leading-relaxed">Douala, Cameroun<br>Akwa, Blvd de la Liberté</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center shrink-0 text-brand-500">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <span class="font-mono text-white tracking-wide">+237 690 58 50 03</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-brand-500/10 flex items-center justify-center shrink-0 text-brand-500">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <a href="mailto:contact@connect-office.cm" class="hover:text-white transition-colors">contact@connect-office.cm</a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Copyright -->
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-600 font-medium">
                <div>
                    &copy; {{ date('Y') }} Connect Office. Engineering Excellence.
                </div>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-slate-300 transition-colors">Mentions Légales</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Confidentialité</a>
                </div>
            </div>

        </div>

    </footer>

</body>
</html>