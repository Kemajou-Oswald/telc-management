@extends('layouts.frontend')

@section('content')

<!-- CONTENEUR MASTER -->
<div class="bg-[#010409] text-slate-200 font-sans overflow-x-hidden relative">

    <!-- TEXTURE DE FOND -->
    <div class="fixed inset-0 z-[0] opacity-[0.03] pointer-events-none bg-grain mix-blend-overlay"></div>

    <!-- ========================================= -->
    <!-- 1. HERO SECTION -->
    <!-- ========================================= -->
    <section class="relative py-32 md:py-40 border-b border-white/5 overflow-hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-brand-900/20 via-[#010409] to-[#010409]"></div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center space-y-6">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-[10px] font-mono uppercase tracking-[0.3em] mb-4">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                Integrated Systems Engineering
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-tight tracking-tighter">
                Solutions <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1E98C5] via-white to-[#9BC53D] animate-gradient-flow">
                    Infrastructurelles.
                </span>
            </h1>

            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto font-light leading-relaxed italic">
                L'ingénierie de précision pour sécuriser, connecter et automatiser vos environnements critiques.
            </p>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- 2. LES SOLUTIONS (STRUCTURE AVEC ILLUSTRATIONS) -->
    <!-- ========================================= -->
    
    @php
    $solutions = [
        [
            'id' => 'security',
            'title' => 'Sécurité Électronique',
            'subtitle' => 'Protection Périmétrique & IA',
            'desc' => 'Une surveillance totale et une gestion des flux automatisée par intelligence artificielle.',
            'icon' => 'shield-check',
            'color' => 'brand',
            'features' => [
                ['Vidéosurveillance IP', 'IA, lecture de plaques & thermique.'],
                ['Contrôle d’accès', 'Biométrie & Badges RFID cryptés.'],
                ['Temps de Présence', 'Gestion des flux RH & Pointage.'],
                ['Alarme Incendie', 'Détection précoce & Mise en sécurité.'],
                ['Alerte Générale', 'Sonorisation d\'évacuation pro.'],
                ['Interphonie IP', 'Systèmes de guichet & Portiers.'],
                ['Files d’attente', 'Gestion électronique de l\'accueil.'],
                ['Gestion de Ronde', 'Suivi temps réel des agents.'],
                ['Sonorisation', 'Ambiance & Public Address.'],
            ],
            'illu_type' => 'radar'
        ],
        [
            'id' => 'network',
            'title' => 'Réseau & Télécom',
            'subtitle' => 'Connectivité & Énergie Critique',
            'desc' => 'Le système nerveux de votre entreprise : rapide, sécurisé et protégé contre les pannes électriques.',
            'icon' => 'network',
            'color' => 'blue',
            'features' => [
                ['Câblage Structuré', 'LAN Cuivre & Fibre Optique certifiée.'],
                ['Réseau Ondulé', 'Câblage dédié pour équipements IT.'],
                ['Onduleurs Haute Capacité', 'Protection de puissance (KVA).'],
                ['WiFi Haute Densité', 'Roaming fluide & Portail captif.'],
                ['Liaisons Radio', 'Point à Point & Multipoint.'],
                ['Radiocommunication', 'Talkie-Walkies portée 1Km+.'],
                ['Cybersécurité', 'Firewalls & VPN Next-Gen.'],
                ['Urbanisation Baies', 'Aménagement de salles serveurs.'],
            ],
            'illu_type' => 'nodes'
        ],
        [
            'id' => 'energy',
            'title' => 'Énergies Renouvelables',
            'subtitle' => 'Autonomie & Efficacité Verte',
            'desc' => 'Indépendance électrique totale grâce au stockage hybride et aux panneaux haute performance.',
            'icon' => 'bolt',
            'color' => 'accent',
            'features' => [
                ['Photovoltaïque', 'Panneaux monocristallins Grade A.'],
                ['Stockage Hybride', 'Backup & Batteries Lithium-Ion.'],
                ['Onduleurs Solaires', 'Conversion & Injection intelligente.'],
                ['Éclairage Public', 'Lampadaires solaires LED autonomes.'],
                ['Monitoring Cloud', 'Suivi de production via App mobile.'],
                ['Audit Énergétique', 'Optimisation de la consommation.'],
            ],
            'illu_type' => 'solar'
        ],
        [
            'id' => 'gtb',
            'title' => 'GTB & Domotique',
            'subtitle' => 'Bâtiment Connecté & Smart Home',
            'desc' => 'Pilotage centralisé de vos équipements pour un confort optimal et des économies d\'énergie drastiques.',
            'icon' => 'building',
            'color' => 'purple',
            'features' => [
                ['Domotique de Luxe', 'Automation résidentielle & Bureaux.'],
                ['Pilotage CVC', 'Gestion intelligente climatisation.'],
                ['Smart Lighting', 'Détection présence & Gradation.'],
                ['Comptage Énergie', 'Analyse précise par zone/usage.'],
                ['Supervision GTC', 'Interface de contrôle centralisée.'],
                ['Maintenance IT', 'Alertes pannes prédictives.'],
            ],
            'illu_type' => 'building'
        ]
    ];
    @endphp

    @foreach($solutions as $index => $sol)
    @php 
        $colorHex = $sol['color'] === 'accent' ? '#9BC53D' : ($sol['color'] === 'purple' ? '#a855f7' : '#1E98C5');
        $colorClass = $sol['color'] === 'accent' ? 'text-[#9BC53D]' : ($sol['color'] === 'purple' ? 'text-purple-400' : 'text-brand-400');
        $isEven = $index % 2 === 0;
    @endphp

    <section id="{{ $sol['id'] }}" class="relative py-24 lg:py-32 border-b border-white/5 overflow-hidden group">
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                
                <!-- COLONNE TEXTE & GRILLE (ORDRE ALTERNÉ) -->
                <div class="{{ $isEven ? 'order-1' : 'order-1 lg:order-2' }} space-y-10">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-3 text-xs font-mono font-bold tracking-widest {{ $colorClass }} uppercase">
                            <span class="p-2 rounded-lg bg-white/5 border border-white/10">0{{ $index + 1 }}</span>
                            {{ $sol['subtitle'] }}
                        </div>
                        <h2 class="text-4xl md:text-6xl font-black text-white leading-tight tracking-tighter">
                            {{ $sol['title'] }}.
                        </h2>
                        <p class="text-lg text-slate-400 font-light leading-relaxed">
                            {{ $sol['desc'] }}
                        </p>
                    </div>

                    <!-- Grille compacte des services -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($sol['features'] as $feat)
                        <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5 hover:border-white/20 hover:bg-white/[0.05] transition-all flex items-center gap-4 group/item">
                            <div class="w-1.5 h-1.5 rounded-full {{ $sol['color'] === 'accent' ? 'bg-[#9BC53D]' : ($sol['color'] === 'purple' ? 'bg-purple-500' : 'bg-brand-500') }} group-hover/item:scale-150 transition-transform"></div>
                            <span class="text-sm font-medium text-slate-300 group-hover/item:text-white transition-colors">{{ $feat[0] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- COLONNE ILLUSTRATION (DASHBOARD VISUEL) -->
                <div class="{{ $isEven ? 'order-2' : 'order-2 lg:order-1' }} relative h-[400px] lg:h-[550px] flex items-center justify-center">
                    
                    <!-- Le Conteneur de l'Illustration -->
                    <div class="relative w-full h-full max-w-md bg-gradient-to-br from-white/[0.03] to-transparent rounded-[3rem] border border-white/10 overflow-hidden group/illu shadow-2xl">
                        
                        <!-- Header Style Interface -->
                        <div class="absolute top-0 left-0 w-full h-12 border-b border-white/5 flex items-center px-6 justify-between bg-black/20 backdrop-blur-md">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-500/30 border border-red-500/50"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/30 border border-yellow-500/50"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-500/30 border border-green-500/50"></div>
                            </div>
                            <span class="text-[9px] font-mono text-slate-500 tracking-widest uppercase">{{ $sol['id'] }}_active.sys</span>
                        </div>

                        <!-- ILLUSTRATIONS DYNAMIQUES SELON LE TYPE -->
                        <div class="w-full h-full flex items-center justify-center p-12">
                            
                            <!-- 1. ILLUSTRATION RADAR (SECURITE) -->
                            @if($sol['illu_type'] == 'radar')
                            <div class="relative w-64 h-64 border-2 border-brand-500/20 rounded-full flex items-center justify-center">
                                <div class="absolute inset-0 border-t-2 border-brand-500 animate-spin-slow rounded-full"></div>
                                <div class="absolute inset-4 border border-brand-500/10 rounded-full"></div>
                                <div class="absolute inset-12 border border-brand-500/10 rounded-full"></div>
                                <svg class="w-20 h-20 text-brand-400 drop-shadow-[0_0_15px_rgba(30,152,197,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <div class="absolute top-10 right-10 w-2 h-2 bg-red-500 rounded-full animate-ping"></div>
                            </div>
                            @endif

                            <!-- 2. ILLUSTRATION NODES (RESEAU) -->
                            @if($sol['illu_type'] == 'nodes')
                            <div class="relative w-64 h-64 flex items-center justify-center">
                                <div class="grid grid-cols-3 gap-8 relative z-10">
                                    @for($i=0; $i<9; $i++)
                                    <div class="w-4 h-4 rounded-full bg-blue-500/20 border border-blue-500/50 {{ $i == 4 ? 'bg-blue-500 shadow-[0_0_20px_#3b82f6]' : '' }}"></div>
                                    @endfor
                                </div>
                                <svg class="absolute inset-0 w-full h-full text-blue-500/10" viewBox="0 0 100 100">
                                    <line x1="20" y1="20" x2="80" y2="80" stroke="currentColor" stroke-width="0.5" />
                                    <line x1="80" y1="20" x2="20" y2="80" stroke="currentColor" stroke-width="0.5" />
                                    <circle cx="50" cy="50" r="40" stroke="currentColor" fill="none" stroke-dasharray="4 4" class="animate-spin-slow" />
                                </svg>
                                <div class="absolute bottom-0 text-[10px] font-mono text-blue-400">TRAFFIC_LOAD: 24.5 GB/s</div>
                            </div>
                            @endif

                            <!-- 3. ILLUSTRATION SOLAR (ENERGIE) -->
                            @if($sol['illu_type'] == 'solar')
                            <div class="relative w-64 h-64 flex items-center justify-center">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_#9BC53D_0%,_transparent_70%)] opacity-10 animate-pulse"></div>
                                <div class="w-32 h-40 border-2 border-[#9BC53D]/30 rounded-xl relative overflow-hidden flex flex-col justify-end p-2 bg-black/20">
                                    <div class="w-full bg-[#9BC53D] rounded-lg animate-battery-charge" style="height: 60%"></div>
                                    <div class="absolute top-4 left-1/2 -translate-x-1/2 text-[10px] font-bold text-white uppercase">Charging</div>
                                </div>
                                <div class="absolute -top-4 text-[#9BC53D] animate-bounce">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                            </div>
                            @endif

                            <!-- 4. ILLUSTRATION BUILDING (GTB) -->
                            @if($sol['illu_type'] == 'building')
                            <div class="relative w-64 h-64 flex items-center justify-center">
                                <div class="w-24 h-48 border-2 border-purple-500/30 rounded-t-xl relative flex flex-wrap gap-2 p-3 overflow-hidden">
                                    @for($i=0; $i<12; $i++)
                                    <div class="w-4 h-3 bg-purple-500/10 rounded-sm animate-pulse" style="animation-delay: {{ $i*0.2 }}s"></div>
                                    @endfor
                                    <div class="absolute inset-0 bg-gradient-to-t from-purple-500/20 to-transparent"></div>
                                </div>
                                <div class="absolute top-0 right-0 p-3 bg-black/40 border border-purple-500/30 rounded-xl backdrop-blur-md">
                                    <div class="text-[10px] text-purple-300 font-mono">TEMP: 22.4°C</div>
                                    <div class="text-[10px] text-purple-300 font-mono">HVAC: AUTO</div>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>

                    <!-- Glow derrière l'illustration -->
                    <div class="absolute inset-0 {{ $sol['color'] === 'accent' ? 'bg-[#9BC53D]/5' : ($sol['color'] === 'purple' ? 'bg-purple-500/5' : 'bg-brand-500/5') }} blur-[100px] rounded-full -z-10"></div>
                </div>

            </div>
        </div>
    </section>
    @endforeach

    <!-- ========================================= -->
    <!-- 3. CTA FINAL -->
    <!-- ========================================= -->
    <section class="relative py-32 overflow-hidden border-t border-white/5">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_var(--tw-gradient-stops))] from-brand-900/20 via-[#010409] to-[#010409]"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center space-y-10">
            <h2 class="text-5xl md:text-7xl font-black text-white tracking-tighter leading-tight">
                Prêt à configurer <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-[#9BC53D]">votre système ?</span>
            </h2>

            <p class="text-lg md:text-xl text-slate-400 font-light max-w-3xl mx-auto italic">
                Nos experts sont à votre disposition pour transformer vos besoins techniques en infrastructure performante.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-6 pt-8">
                <a href="{{ route('contact') }}"
                   class="group relative px-12 py-6 bg-white text-brand-900 rounded-3xl font-black text-xl shadow-[0_0_50px_rgba(255,255,255,0.2)] hover:scale-105 transition-all overflow-hidden text-center">
                    <span class="relative z-10">Démarrer une étude</span>
                </a>
                
                <a href="https://wa.me/votre_numero"
                   class="px-12 py-6 border border-white/10 text-white rounded-3xl font-black text-xl hover:bg-white/5 transition-all flex items-center justify-center gap-3">
                   <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                   WhatsApp Direct
                </a>
            </div>
        </div>
    </section>

</div>

<style>
    /* Gradient Flow Animation */
    @keyframes gradient-flow {
        0% { background-position: 0% 50%; }
        100% { background-position: 200% 50%; }
    }
    .animate-gradient-flow {
        background-size: 200% auto;
        animation: gradient-flow 6s linear infinite;
    }

    /* Radar/Building Spin */
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 12s linear infinite;
    }

    /* Battery Animation */
    @keyframes battery-charge {
        0% { height: 10%; opacity: 0.5; }
        50% { height: 90%; opacity: 1; }
        100% { height: 10%; opacity: 0.5; }
    }
    .animate-battery-charge {
        animation: battery-charge 4s ease-in-out infinite;
    }

    /* Grain Texture */
    .bg-grain {
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    }

    /* Smooth Perspective */
    .perspective-2000 {
        perspective: 2000px;
    }
</style>

@endsection