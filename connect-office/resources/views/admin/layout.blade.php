<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Connect Office')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            400: '#3bb3d6',
                            500: '#1E98C5',
                            600: '#167ca3'
                        },
                        accent: '#9BC53D'
                    }
                }
            }
        }
    </script>

</head>

<body class="bg-[#010409] text-slate-200 font-sans overflow-x-hidden">

    <!-- ================= HEADER ================= -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-[#010409]/80 border-b border-white/5">

        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-connect-office.png') }}"
                    alt="Connect Office"
                    class="h-10 w-auto transition group-hover:scale-105">
            </a>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center gap-10 text-sm font-medium">

                <a href="{{ route('home') }}"
                    class="hover:text-brand-400 transition-colors">
                    Accueil
                </a>

                <a href="{{ route('about') }}"
                    class="hover:text-brand-400 transition-colors">
                    Qui sommes-nous
                </a>

                <a href="{{ route('solutions') }}"
                    class="hover:text-brand-400 transition-colors">
                    Nos Solutions
                </a>

                <a href="{{ route('catalogue') }}"
                    class="hover:text-brand-400 transition-colors">
                    Catalogue
                </a>

                <a href="{{ route('contact') }}"
                    class="hover:text-brand-400 transition-colors">
                    Contact
                </a>

            </nav>




        </div>

    </header>
    <div
        x-data="{ show: @json(session('success') ? true : false) }"
        x-show="show"
        x-transition
        class="fixed bottom-6 right-6 bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg"
        style="display:none">
        Demande envoyée avec succès.
    </div>

    <!-- ================= PAGE CONTENT ================= -->
    <main class="min-h-screen">

        @yield('content')

    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="border-t border-white/5 bg-black/30 backdrop-blur-lg">

        <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-4 gap-12 text-sm">

            <!-- Bloc 1 -->
            <div>
                <img src="{{ asset('images/logo-connect-office.png') }}"
                    class="h-10 mb-6">

                <p class="text-slate-400 leading-relaxed">
                    Connect Office conçoit et déploie des infrastructures
                    critiques en sécurité électronique, réseaux professionnels,
                    énergie renouvelable et gestion technique des bâtiments.
                </p>
            </div>

            <!-- Solutions -->
            <div>
                <h3 class="font-bold text-white mb-6 tracking-wide">Solutions</h3>
                <ul class="space-y-3 text-slate-400">
                    <li><a href="{{ route('solutions') }}" class="hover:text-white transition">Sécurité électronique</a></li>
                    <li><a href="{{ route('solutions') }}" class="hover:text-white transition">Réseaux & Télécom</a></li>
                    <li><a href="{{ route('solutions') }}" class="hover:text-white transition">Énergies renouvelables</a></li>
                    <li><a href="{{ route('solutions') }}" class="hover:text-white transition">Gestion Technique (GTB)</a></li>
                </ul>
            </div>

            <!-- Navigation -->
            <div>
                <h3 class="font-bold text-white mb-6 tracking-wide">Navigation</h3>
                <ul class="space-y-3 text-slate-400">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">Qui sommes-nous</a></li>
                    <li><a href="{{ route('catalogue') }}" class="hover:text-white transition">Catalogue</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="font-bold text-white mb-6 tracking-wide">Contact</h3>
                <p class="text-slate-400 leading-relaxed">
                    Douala, Cameroun<br>
                    +237 690 58 50 03<br>
                    contact@connect-office.cm
                </p>
            </div>

        </div>

        <div class="border-t border-white/5 py-6 text-center text-slate-500 text-xs">
            © {{ date('Y') }} Connect Office — Tous droits réservés.
        </div>

    </footer>
    @yield('scripts')
</body>
</html>