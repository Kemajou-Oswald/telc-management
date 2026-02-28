@extends('layouts.frontend')

@section('content')

<!-- CONTENEUR MASTER -->
<div class="bg-[#010409] text-slate-200 font-sans overflow-x-hidden relative">

    <!-- OVERLAY TEXTURE -->
    <div class="fixed inset-0 z-[1] opacity-[0.04] pointer-events-none bg-grain mix-blend-overlay"></div>

    <!-- ========================================= -->
    <!-- 1. HERO SECTION : IDENTITÉ -->
    <!-- ========================================= -->
    <section
        x-data="{ 
            mouseX: 0, 
            mouseY: 0,
            init() {
                window.addEventListener('mousemove', (e) => {
                    this.mouseX = (e.clientX / window.innerWidth) * 100;
                    this.mouseY = (e.clientY / window.innerHeight) * 100;
                })
            }
        }"
        class="relative py-32 md:py-48 border-b border-white/5 overflow-hidden flex items-center justify-center">

        <!-- Background interactif -->
        <div class="absolute inset-0 z-0 opacity-[0.15] pointer-events-none transition-all duration-1000 ease-out"
            :style="`background: radial-gradient(circle at ${mouseX}% ${mouseY}%, #1E98C5 0%, transparent 40%)`">
        </div>

        <!-- Grille tech -->
        <div class="absolute inset-0 z-0 opacity-[0.1] pointer-events-none"
            style="background-image: linear-gradient(#1E98C5 0.5px, transparent 0.5px), linear-gradient(90deg, #1E98C5 0.5px, transparent 0.5px); background-size: 50px 50px; mask-image: radial-gradient(circle at 50% 50%, black, transparent 80%);">
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center space-y-8">

            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-4">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                <span class="text-[10px] font-mono tracking-[0.3em] text-slate-300 uppercase">
                    Connect Office Corporate
                </span>
            </div>

            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-tight tracking-tighter">
                L'ingénierie au service <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1E98C5] via-white to-[#9BC53D] animate-gradient-flow">
                    des infrastructures critiques.
                </span>
            </h1>

            <p class="text-lg md:text-xl text-slate-400 max-w-3xl mx-auto font-light leading-relaxed">
                Connect Office accompagne les entreprises, institutions et PME
                dans la <span class="text-white font-medium">conception, le déploiement et la supervision</span>
                d’infrastructures techniques sécurisées et performantes.
            </p>

        </div>
    </section>
    <!-- ========================================= -->
    <!-- SECTION : QUI SOMMES-NOUS (L'ADN INGÉNIERIE) -->
    <!-- ========================================= -->
    <section id="a-propos" class="relative py-32 border-b border-white/5 overflow-hidden">

        <!-- Glow discret en arrière-plan -->
        <div class="absolute top-1/2 right-0 -translate-y-1/2 w-[600px] h-[600px] bg-brand-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-12 gap-16 items-center">

                <!-- GAUCHE : LE TEXTE IDENTITAIRE -->
                <div class="lg:col-span-6 space-y-8">
                    <div class="inline-flex items-center gap-3 px-3 py-1 rounded-full bg-white/5 border border-white/10">
                        <span class="text-[10px] font-mono font-bold tracking-[0.2em] text-brand-400 uppercase">Qui sommes-nous</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight tracking-tighter">
                        Une force d'ingénierie <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1E98C5] to-[#9BC53D]">
                            multidisciplinaire.
                        </span>
                    </h2>

                    <div class="space-y-6 text-slate-400 text-lg font-light leading-relaxed">
                        <p>
                            Connect Office n'est pas qu'un simple prestataire de services. Nous sommes un collectif d'ingénieurs et de techniciens passionnés, spécialisés dans l'intégration de solutions technologiques complexes pour les environnements professionnels exigeants.
                        </p>
                        <p>
                            Depuis notre base au <span class="text-white font-medium">Cameroun</span>, nous maîtrisons l'ensemble de la chaîne de valeur technique : de l'audit initial à la conception de schémas directeurs, jusqu'au déploiement de systèmes de sécurité haute fidélité et de réseaux critiques.
                        </p>
                        <p class="border-l-2 border-brand-500 pl-6 italic bg-white/5 py-4 rounded-r-2xl">
                            "Notre mission est de transformer la complexité technologique en simplicité opérationnelle pour nos clients."
                        </p>
                    </div>
                </div>

                <!-- DROITE : DASHBOARD DE COMPÉTENCES (VISUEL IMPACTANT) -->
                <div class="lg:col-span-6 relative">
                    <div class="glass-panel p-8 md:p-12 relative overflow-hidden group">
                        <!-- Scanline effect -->
                        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-brand-500/40 to-transparent animate-scan"></div>

                        <div class="space-y-10">
                            <div class="flex justify-between items-end">
                                <h3 class="text-xs font-mono font-bold text-brand-400 uppercase tracking-[0.3em]">Core Expertise Matrix</h3>
                                <div class="flex gap-1">
                                    <span class="w-1 h-1 rounded-full bg-brand-500 animate-pulse"></span>
                                    <span class="w-1 h-1 rounded-full bg-brand-500 animate-pulse delay-75"></span>
                                    <span class="w-1 h-1 rounded-full bg-brand-500 animate-pulse delay-150"></span>
                                </div>
                            </div>

                            <!-- Liste des expertises avec barres de progression tech -->
                            <div class="space-y-8">
                                @php
                                $expertises = [
                                ['Sécurité & Surveillance', 'Architecture IP, biométrie et IA vidéo.', '95%'],
                                ['Réseaux & Télécoms', 'Fibre optique, SD-WAN et haute disponibilité.', '98%'],
                                ['Efficacité Énergétique', 'Solaire hybride et monitoring GTB.', '90%'],
                                ['Systèmes Intégrés', 'Interconnexion et supervision centralisée.', '94%']
                                ];
                                @endphp

                                @foreach($expertises as $exp)
                                <div class="space-y-3 group/item">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-white font-bold tracking-wide">{{ $exp[0] }}</span>
                                        <span class="text-brand-400 font-mono text-xs">{{ $exp[2] }}</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-brand-600 to-brand-400 rounded-full transition-all duration-[2s] group-hover/item:shadow-[0_0_15px_rgba(30,152,197,0.5)]" style="width: {{ $exp[2] }}"></div>
                                    </div>
                                    <p class="text-[11px] text-slate-500 font-light italic">{{ $exp[1] }}</p>
                                </div>
                                @endforeach
                            </div>

                            <!-- Badge de certification -->
                            <div class="pt-8 border-t border-white/5 flex items-center gap-4 text-xs text-slate-500 font-mono italic">
                                <svg class="w-5 h-5 text-[#9BC53D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Expertise certifiée & Équipes qualifiées
                            </div>
                        </div>
                    </div>

                    <!-- Décoration flottante derrière le dashboard -->
                    <div class="absolute -z-10 -bottom-6 -right-6 w-32 h-32 border border-brand-500/20 rounded-3xl rotate-12"></div>
                    <div class="absolute -z-10 -top-6 -left-6 w-24 h-24 border border-[#9BC53D]/20 rounded-full"></div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- 2. MISSION & VISION (DUAL CARDS) -->
    <!-- ========================================= -->
    <section class="relative py-24 border-b border-white/5">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1px] h-full bg-gradient-to-b from-transparent via-white/10 to-transparent hidden lg:block"></div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 lg:gap-24">

            <!-- Mission -->
            <div class="group relative p-8 md:p-12 rounded-3xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.04] transition-colors duration-500">
                <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-brand-500/50 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>

                <div class="w-14 h-14 rounded-2xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-white mb-6">Notre Mission</h2>
                <p class="text-slate-400 leading-relaxed font-light text-lg">
                    Concevoir et déployer des solutions techniques fiables
                    permettant aux organisations de <span class="text-white">sécuriser leurs actifs</span>,
                    optimiser leurs performances et garantir la continuité
                    de leurs opérations critiques.
                </p>
            </div>

            <!-- Vision -->
            <div class="group relative p-8 md:p-12 rounded-3xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.04] transition-colors duration-500">
                <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-[#9BC53D]/50 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>

                <div class="w-14 h-14 rounded-2xl bg-[#9BC53D]/10 border border-[#9BC53D]/20 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-[#9BC53D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-white mb-6">Notre Vision</h2>
                <p class="text-slate-400 leading-relaxed font-light text-lg">
                    Devenir le partenaire technologique de référence
                    en Afrique centrale pour la <span class="text-white">sécurité,
                        les réseaux professionnels</span>, les énergies intelligentes
                    et la gestion technique des bâtiments.
                </p>
            </div>

        </div>
    </section>

    <!-- ========================================= -->
    <!-- 3. APPROCHE (PROCESS FLOW) -->
    <!-- ========================================= -->
    <section class="relative py-32 border-b border-white/5 bg-black/20 overflow-hidden">

        <!-- Glow background -->
        <div class="absolute right-0 top-1/4 w-[800px] h-[800px] bg-brand-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">

            <div class="max-w-3xl mb-20">
                <div class="inline-flex items-center gap-3 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6">
                    <span class="text-[10px] font-mono font-bold tracking-[0.2em] text-brand-400 uppercase">Méthodologie</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">
                    Une approche structurée <br> et maîtrisée.
                </h2>
                <p class="text-slate-400 font-light text-lg max-w-2xl">
                    Chaque projet suit une méthodologie rigoureuse garantissant performance,
                    conformité et pérennité de vos installations.
                </p>
            </div>

            <!-- GRID PROCESS -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                $steps = [
                ['01', 'Audit & Analyse', 'Étude approfondie de vos besoins et infrastructures existantes.'],
                ['02', 'Conception', 'Élaboration d’une architecture technique adaptée et évolutive.'],
                ['03', 'Déploiement', 'Installation conforme aux standards professionnels les plus stricts.'],
                ['04', 'Maintenance', 'Suivi continu, supervision et optimisation des performances.']
                ];
                @endphp

                @foreach($steps as $step)
                <div class="relative p-8 rounded-2xl bg-white/[0.03] border border-white/5 group hover:bg-white/[0.05] transition-all duration-300">
                    <!-- Numéro en background -->
                    <div class="absolute -top-4 -right-2 text-6xl font-black text-white/[0.02] group-hover:text-brand-500/10 transition-colors font-sans z-0">
                        {{ $step[0] }}
                    </div>

                    <div class="relative z-10">
                        <div class="w-10 h-10 rounded-full border border-white/10 bg-white/5 flex items-center justify-center mb-6 group-hover:border-brand-500/50 group-hover:text-brand-400 transition-colors">
                            <span class="font-mono text-xs font-bold">{{ $step[0] }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-3 group-hover:text-brand-400 transition-colors">
                            {{ $step[1] }}
                        </h3>
                        <p class="text-slate-400 text-sm font-light leading-relaxed">
                            {{ $step[2] }}
                        </p>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- 4. NOS DOMAINES D'EXPERTISE (CLIQUABLES) -->
    <!-- ========================================= -->
    <section class="relative py-32 border-b border-white/5">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">
                    Nos domaines d’expertise
                </h2>
                <p class="text-slate-400 font-light text-lg">
                    Une maîtrise complète des infrastructures techniques modernes.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                // Chaque domaine pointe vers une section spécifique de la page Solutions
                $domains = [
                ['Sécurité électronique', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z', 'security'],
                ['Réseaux & Télécom', 'M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0', 'network'],
                ['Énergies renouvelables', 'M13 10V3L4 14h7v7l9-11h-7z', 'energy'],
                ['GTB & Domotique', 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'gtb']
                ];
                @endphp

                @foreach($domains as $d)
                <a href="{{ route('solutions') }}#{{ $d[2] }}"
                    class="group relative p-8 rounded-2xl border border-white/10 bg-white/[0.02] hover:bg-white/[0.05] hover:border-brand-500/30 transition-all duration-300 hover:-translate-y-2 block">

                    <!-- Icône -->
                    <div class="mb-6 text-slate-400 group-hover:text-brand-400 transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d[1] }}" />
                        </svg>
                    </div>

                    <!-- Titre -->
                    <h3 class="text-white font-bold text-lg mb-2 group-hover:text-brand-400 transition-colors flex items-center justify-between">
                        {{ $d[0] }}
                        <!-- Petite flèche qui apparaît au survol -->
                        <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </h3>

                    <!-- Barre décorative -->
                    <div class="w-8 h-[2px] bg-white/10 group-hover:w-full group-hover:bg-brand-500/50 transition-all duration-500 mt-4"></div>
                </a>
                @endforeach

            </div>
        </div>
    </section>
    <!-- ========================================= -->
    <!-- 5. NOS ENGAGEMENTS (VALEURS) -->
    <!-- ========================================= -->
    <section class="relative py-32 overflow-hidden">

        <!-- Fond gradient subtil -->
        <div class="absolute inset-0 bg-gradient-to-t from-brand-900/10 to-transparent pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-6 relative z-10">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div>
                    <h2 class="text-4xl md:text-5xl font-black text-white mb-8 tracking-tighter">
                        Nos engagements <br>
                        <span class="text-brand-400">envers vous.</span>
                    </h2>
                    <p class="text-slate-400 text-lg font-light leading-relaxed mb-8">
                        Au-delà de la technique, Connect Office s'engage sur la qualité de la relation et la durabilité des solutions mises en œuvre.
                    </p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-white font-bold hover:text-brand-400 transition-colors border-b border-brand-500 pb-1">
                        Démarrer un projet avec nous
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="space-y-6">
                    <!-- Valeur 1 -->
                    <div class="flex gap-6 p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:border-brand-500/30 transition-colors group">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-brand-400 transition-colors">Fiabilité</h3>
                            <p class="text-slate-400 text-sm font-light leading-relaxed">
                                Des solutions robustes, testées et conçues pour durer dans les environnements exigeants.
                            </p>
                        </div>
                    </div>

                    <!-- Valeur 2 -->
                    <div class="flex gap-6 p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:border-brand-500/30 transition-colors group">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-brand-400 transition-colors">Transparence</h3>
                            <p class="text-slate-400 text-sm font-light leading-relaxed">
                                Une communication claire, des devis détaillés et un accompagnement constant à chaque étape.
                            </p>
                        </div>
                    </div>

                    <!-- Valeur 3 -->
                    <div class="flex gap-6 p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:border-brand-500/30 transition-colors group">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-brand-400 transition-colors">Excellence Technique</h3>
                            <p class="text-slate-400 text-sm font-light leading-relaxed">
                                Respect strict des normes internationales et utilisation de matériel de pointe certifié.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

</div>

<style>
    .bg-grain {
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    }

    @keyframes gradient-flow {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 200% 50%;
        }
    }

    .animate-gradient-flow {
        animation: gradient-flow 6s linear infinite;
        background-size: 200% auto;
    }

    /* Animation de balayage (scanline) pour le dashboard */
    @keyframes scan {
        0% {
            top: 0%;
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            top: 100%;
            opacity: 0;
        }
    }

    .animate-scan {
        animation: scan 4s linear infinite;
    }
</style>

@endsection