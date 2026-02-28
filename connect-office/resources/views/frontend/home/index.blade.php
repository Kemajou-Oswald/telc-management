@extends('layouts.frontend')

@section('content')
<!-- 1. HERO SECTION -->
<!-- 1. HERO SECTION -->
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
    class="relative min-h-screen flex items-center justify-center pt-32 pb-24 overflow-hidden border-b border-white/5">

    <div class="absolute inset-0 z-0 opacity-[0.2] pointer-events-none transition-all duration-1000 ease-out"
        :style="`background: radial-gradient(circle at ${mouseX}% ${mouseY}%, #1E98C5 0%, transparent 45%)`">
    </div>

    <div class="absolute inset-0 z-0 opacity-[0.1] pointer-events-none"
        style="background-image: linear-gradient(#1E98C5 0.5px, transparent 0.5px), linear-gradient(90deg, #1E98C5 0.5px, transparent 0.5px); background-size: 60px 60px; mask-image: radial-gradient(circle at 50% 50%, black, transparent 80%);">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 w-full grid lg:grid-cols-12 gap-16 items-center">

        <!-- GAUCHE : TEXTE -->
        <div class="lg:col-span-5 space-y-10">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                <div class="flex gap-1">
                    <span class="w-1 h-3 bg-brand-500 animate-bounce"></span>
                    <span class="w-1 h-3 bg-brand-500 animate-bounce" style="animation-delay: 0.15s"></span>
                    <span class="w-1 h-3 bg-brand-500 animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-slate-400">Expertise Systèmes & Intégration</span>
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-[5.2rem] font-black leading-[0.95] tracking-tighter text-white">
                Infrastructures <br>
                <span class="relative inline-block pb-2">
                    <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-brand-500 via-white to-[#9BC53D] bg-[length:200%_auto] animate-shimmer-text">
                        intelligentes.
                    </span>
                    <span class="absolute bottom-0 left-0 w-full h-[3px] bg-gradient-to-r from-brand-500 to-transparent scale-x-0 animate-expand-line origin-left"></span>
                </span>
            </h1>

            <p class="text-lg md:text-xl text-slate-400 max-w-md font-light leading-relaxed">
                Connect Office déploie l'excellence technique au cœur de vos bâtiments. Performance durable et maîtrise totale des flux.
            </p>

            <div class="flex flex-wrap gap-5 pt-4">
                <a href="#services" class="group relative px-8 py-4 bg-brand-500 text-white rounded-2xl font-bold transition-all hover:scale-[1.02] shadow-lg shadow-brand-500/20 overflow-hidden">
                    <span class="relative flex items-center gap-3">Nos Services <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg></span>
                </a>
            </div>
        </div>

        <!-- DROITE : ANIMATION CIRCULAIRE (FIXED POSITION) -->
        <div class="lg:col-span-7 relative h-[700px] mt-12 lg:mt-0 flex items-center justify-center"
            x-data="{ 
                active: 0, 
                cards: [
                    { id: 0, title: 'Sécurité Électronique', icon: 'shield', desc: 'Protection périmétrique avancée, vidéosurveillance intelligente par IA et systèmes de contrôle d’accès biométriques haute précision.' },
                    { id: 1, title: 'Infrastructures Réseaux', icon: 'network', desc: 'Connectivité robuste en fibre optique, architecture LAN/WLAN haute densité et interconnexion de sites distants garantie.' },
                    { id: 2, title: 'Énergies Renouvelables', icon: 'bolt', desc: 'Solutions solaires hybrides et monitoring énergétique en temps réel pour optimiser votre consommation durable.' },
                    { id: 3, title: 'GTB Monitoring', icon: 'chart', desc: 'Gestion Technique centralisée des Bâtiments permettant une supervision globale de vos installations pour une efficacité maximale.' }
                ],
                rotate() {
                    setInterval(() => { this.active = (this.active + 1) % 4 }, 7500); 
                }
            }"
            x-init="rotate()">

            <!-- Ajout d'un padding horizontal (px-10) pour éviter que ça colle aux bords -->
            <div class="relative w-full h-full perspective-2000 px-10">
                <template x-for="(card, index) in cards" :key="card.id">
                    <div
                        class="absolute transition-all duration-[1500ms] cubic-bezier-motion glass-panel overflow-hidden"
                        :class="{
                            /* FOCUS : Centré, en haut, large */
                            'top-10 left-1/2 -translate-x-1/2 w-[480px] h-[360px] z-30 opacity-100 scale-100 border-brand-500/50 bg-brand-500/[0.05] shadow-xl': active === index,
                            
                            /* PETITES : En bas, décollées des bords (calcul left ajusté) */
                            'top-[480px] w-[170px] h-[160px] z-10 opacity-40 scale-[0.85] grayscale-[0.3]': active !== index,
                            
                            /* RÉPARTITION : On utilise left-10 et left-[calc(100%-10px)] pour laisser une marge */
                            'left-10 translate-x-0': (active + 1) % 4 === index,
                            'left-1/2 -translate-x-1/2': (active + 2) % 4 === index,
                            'left-[calc(100%-40px)] -translate-x-full': (active + 3) % 4 === index
                        }">

                        <div class="flex flex-col h-full p-8 lg:p-10 justify-between relative">
                            <div x-show="active === index" class="absolute inset-0 bg-gradient-to-br from-brand-500/5 to-transparent pointer-events-none"></div>

                            <div :class="active === index ? 'w-16 h-16 bg-brand-500 rounded-2xl shadow-xl' : 'w-10 h-10 bg-white/10 rounded-xl'"
                                class="flex items-center justify-center transition-all duration-700 relative z-10">
                                <template x-if="card.icon === 'shield'"><svg class="w-1/2 h-1/2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg></template>
                                <template x-if="card.icon === 'network'"><svg class="w-1/2 h-1/2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0" />
                                    </svg></template>
                                <template x-if="card.icon === 'bolt'"><svg class="w-1/2 h-1/2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg></template>
                                <template x-if="card.icon === 'chart'"><svg class="w-1/2 h-1/2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2" />
                                    </svg></template>
                            </div>

                            <div class="mt-6 relative z-10 transition-all duration-700" :class="active !== index ? 'text-center' : ''">
                                <h3 class="font-bold text-white transition-all duration-700" :class="active === index ? 'text-3xl mb-4 tracking-tight' : 'text-[10px] uppercase tracking-widest leading-none'">
                                    <span x-text="card.title"></span>
                                </h3>
                                <p x-show="active === index" x-transition.opacity.duration.700ms class="text-slate-400 text-base leading-relaxed font-light" x-text="card.desc"></p>
                            </div>

                            <div x-show="active === index" class="w-full h-[2px] bg-white/5 mt-6 relative overflow-hidden">
                                <div class="absolute top-0 left-0 h-full bg-brand-500 animate-progress-loop"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════════════
         §2 · SERVICES
         ═══════════════════════════════════════════════════════════════ --}}
<section id="services" class="relative py-36 border-t border-white/[0.04]">

    {{-- Decorations --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-px bg-gradient-to-r from-transparent via-brand-500/25 to-transparent"></div>
    <div class="absolute top-1/3 left-0 w-[400px] h-[400px] rounded-full bg-brand-500/[0.04] blur-[120px] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">

        {{-- Section header --}}
        <div class="grid lg:grid-cols-2 gap-16 items-end mb-24">
            <div class="space-y-7">
                <div class="co-label-pill">Cycle de Performance</div>
                <h2 class="text-[clamp(2.4rem,5vw,4.5rem)] font-black text-white leading-[1.04] tracking-[-0.035em]">
                    Un accompagnement<br>
                    <span class="co-gradient-text">maîtrisé de A à Z.</span>
                </h2>
            </div>
            <p class="text-lg text-slate-400 font-light leading-[1.8] border-l-2 border-brand-500/30 pl-8">
                Nous fusionnons conseil stratégique et haute technicité pour garantir la
                <span class="text-white font-medium">résilience de vos infrastructures</span> critiques.
            </p>
        </div>

        {{-- Services grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
            $services = [
            ['Audit & Analyse', 'Analyse rigoureuse de vos infrastructures pour identifier risques et opportunités.', '01', 'SURVEY' ],
            ['Étude Technique', 'Élaboration de solutions sur-mesure adaptées à vos objectifs stratégiques.', '02', 'BLUEPRINT'],
            ['Mise en service', 'Déploiement professionnel respectant les normes les plus strictes du marché.', '03', 'DEPLOY' ],
            ['Maintenance', 'Suivi technique proactif pour garantir la continuité de vos opérations.', '04', 'READY' ],
            ['Infogérance', 'Externalisation et solutions de location-vente flexibles pour votre budget.', '05', 'MANAGED' ],
            ['Innovation Rapide', 'Intégration de technologies modernes pour booster votre efficacité opérationnelle.', '06', 'SYNC' ],
            ];
            @endphp

            @foreach($services as $idx => $s)
            <div class="co-service-card group" style="animation-delay: {{ $idx * 0.07 }}s">
                {{-- Shimmer top border --}}
                <div class="absolute top-0 left-0 h-px w-full bg-gradient-to-r from-transparent via-brand-500/40 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-[1.4s]"></div>

                <div class="flex justify-between items-start mb-8">
                    <span class="font-mono text-[10px] font-bold text-slate-600 tracking-[.2em] uppercase group-hover:text-brand-400 transition-colors">
                        {{ $s[2] }} / {{ $s[3] }}
                    </span>
                    <div class="co-arrow-btn group-hover:bg-brand-500 group-hover:border-brand-500 transition-all">
                        <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-white tracking-tight mb-3 group-hover:text-brand-400 transition-colors leading-snug">
                    {{ $s[0] }}
                </h3>
                <p class="text-sm text-slate-500 font-light leading-[1.75] flex-grow">{{ $s[1] }}</p>

                <div class="mt-8 pt-6 border-t border-white/[0.05] flex justify-between items-center">
                    <div class="flex gap-1.5 items-center">
                        <span class="w-1 h-1 rounded-full bg-slate-700 group-hover:bg-brand-500 transition-colors"></span>
                        <span class="w-6 h-[2px] rounded-full bg-slate-700 group-hover:bg-brand-500 transition-colors delay-75"></span>
                    </div>
                    <span class="text-[9px] font-bold text-slate-600 uppercase tracking-[.2em] opacity-0 group-hover:opacity-100 translate-y-1 group-hover:translate-y-0 transition-all">Expertise Active</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
         §3 · POURQUOI NOUS
         ═══════════════════════════════════════════════════════════════ --}}
<section class="relative py-28 border-t border-white/[0.04] bg-black/30">
    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-12 gap-20 items-start">

            {{-- Left --}}
            <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-36">
                <div class="co-label-pill">ADN & Valeurs</div>
                <h2 class="text-[clamp(2rem,4.5vw,3.8rem)] font-black text-white leading-[1.05] tracking-[-0.035em]">
                    Pourquoi<br>
                    <span class="co-gradient-text">nous choisir ?</span>
                </h2>
                <p class="text-base text-slate-500 font-light leading-[1.8]">
                    Nous ne sommes pas de simples installateurs. Nous sommes vos partenaires stratégiques pour une infrastructure critique zéro défaut.
                </p>
                <div class="w-16 h-px bg-gradient-to-r from-brand-500 to-transparent"></div>
            </div>

            {{-- Right --}}
            <div class="lg:col-span-8 grid md:grid-cols-2 gap-x-10 gap-y-9">
                @php
                $features = [
                ['Vision Globale', 'Audit, déploiement et maintenance : une maîtrise totale du cycle de vie de vos systèmes.'],
                ['Fiabilité Mesurable', 'Haute disponibilité et réduction drastique des risques opérationnels grâce à nos SLA stricts.'],
                ['Technologies Évolutives', 'Solutions conçues pour s\'adapter et grandir avec vos ambitions sur le long terme.'],
                ['Support Prioritaire', 'Équipe technique réactive disponible pour garantir la continuité de vos opérations 24/7.'],
                ['Approche Sur-Mesure', 'Chaque étude est calibrée selon vos contraintes budgétaires et vos exigences techniques.'],
                ['Confiance Durable', 'Transparence et rigueur méthodologique pour des partenariats solides sur la durée.'],
                ];
                @endphp

                @foreach($features as $i => $f)
                <div class="flex gap-4 group" style="animation-delay: {{ $i * 0.06 }}s">
                    <div class="shrink-0 mt-0.5">
                        <div class="w-9 h-9 rounded-xl bg-brand-500/[0.08] border border-brand-500/20 flex items-center justify-center group-hover:bg-brand-500 group-hover:border-brand-500 group-hover:scale-105 transition-all duration-300">
                            <svg class="w-4 h-4 text-brand-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base tracking-tight group-hover:text-brand-400 transition-colors">{{ $f[0] }}</h3>
                        <p class="text-slate-500 text-sm leading-[1.7] mt-1.5 font-light">{{ $f[1] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
         §4 · MATRICE DES RÉALISATIONS
         ═══════════════════════════════════════════════════════════════ --}}
<section id="realisations" class="relative py-36 border-t border-white/[0.04] overflow-hidden">

    {{-- Glows --}}
    <div class="absolute top-1/2 left-0 -translate-y-1/2 w-[500px] h-[600px] rounded-full bg-brand-500/[0.07] blur-[150px] pointer-events-none"></div>
    <div class="absolute top-0 right-0 w-[350px] h-[350px] rounded-full bg-[#9BC53D]/[0.05] blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="mb-20 max-w-3xl space-y-6">
            <div class="co-label-pill">Expertise Terrain</div>
            <h2 class="text-[clamp(2.4rem,5vw,4.5rem)] font-black text-white leading-[1.04] tracking-[-0.035em]">
                Matrice d'intervention<br>
                <span class="co-gradient-text">& confiance client.</span>
            </h2>
        </div>

        {{-- Table header --}}
        <div class="hidden md:grid grid-cols-12 px-8 mb-4">
            <div class="col-span-4 text-[9px] font-black uppercase tracking-[.3em] text-slate-600">Domaine</div>
            <div class="col-span-5 text-[9px] font-black uppercase tracking-[.3em] text-slate-600 text-center">Références</div>
            <div class="col-span-3 text-[9px] font-black uppercase tracking-[.3em] text-slate-600 text-right">Technologie</div>
        </div>

        {{-- Matrix rows --}}
        @php
        $matrix = [
        [
        'category' => 'Sécurité Électronique',
        'tag' => 'Vidéosurveillance & Contrôle d\'accès',
        'partners' => ['Groupe Monkam', 'Africa Golden Bank', 'Pentagone Hôtel', 'Carrefour Market'],
        'tech' => 'Vidéosurveillance IA',
        'hue' => '#1E98C5',
        'icon' => 'shield',
        ],
        [
        'category' => 'Infrastructures Réseaux',
        'tag' => 'LAN / WLAN / SD-WAN',
        'partners' => ['MRS Corlay', 'IUGET Campus', 'Mahima Holding'],
        'tech' => 'Fibre & SD-WAN',
        'hue' => '#63b3ed',
        'icon' => 'network',
        ],
        [
        'category' => 'Énergie & GTB',
        'tag' => 'Solaire hybride & monitoring',
        'partners' => ['Résidences Monkam', 'Sites Industriels', 'Showrooms Retail'],
        'tech' => 'Solaire + BMS',
        'hue' => '#9BC53D',
        'icon' => 'bolt',
        ],
        ];
        @endphp

        <div class="space-y-3">
            @foreach($matrix as $row)
            <div class="co-matrix-row group" style="--row-color: {{ $row['hue'] }}">

                {{-- Left accent bar --}}
                <div class="absolute left-0 top-0 w-[3px] h-full rounded-l-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    style="background: {{ $row['hue'] }}"></div>

                <div class="grid md:grid-cols-12 items-center gap-8 py-8 px-8">

                    {{-- Category --}}
                    <div class="col-span-4 flex items-center gap-5">
                        <div class="co-matrix-icon" style="--row-color: {{ $row['hue'] }}">
                            @if($row['icon'] === 'shield')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            @elseif($row['icon'] === 'network')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0" />
                            </svg>
                            @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white tracking-tight group-hover:text-white transition-colors leading-snug">{{ $row['category'] }}</h3>
                            <p class="text-[10px] font-mono text-slate-600 mt-0.5 uppercase tracking-[.15em]">{{ $row['tag'] }}</p>
                        </div>
                    </div>

                    {{-- Partners --}}
                    <div class="col-span-5">
                        <div class="flex flex-wrap justify-center gap-2">
                            @foreach($row['partners'] as $p)
                            <span class="co-partner-tag" style="--row-color: {{ $row['hue'] }}">{{ $p }}</span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Technology --}}
                    <div class="col-span-3 text-right">
                        <span class="text-sm font-bold italic text-white tracking-tight">{{ $row['tech'] }}</span>
                        <div class="mt-2 h-px w-full bg-gradient-to-l to-transparent" style="background-image: linear-gradient(to left, {{ $row['hue'] }}, transparent)"></div>
                    </div>
                </div>

                {{-- BG glow per row --}}
                <div class="absolute -right-16 -bottom-16 w-56 h-56 rounded-full blur-[80px] opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"
                    style="background: {{ $row['hue'] }}22"></div>
            </div>
            @endforeach
        </div>

        {{-- Scrolling tech ribbon --}}
        <div class="mt-24 pt-10 border-t border-white/[0.05] overflow-hidden">
            <div class="co-ribbon-track">
                @for ($r = 0; $r < 5; $r++)
                    <span class="co-ribbon-item"><span class="w-1.5 h-1.5 rounded-full bg-brand-500 inline-block"></span> Audit de Performance</span>
                    <span class="co-ribbon-item"><span class="w-1.5 h-1.5 rounded-full bg-[#9BC53D] inline-block"></span> Résilience Infrastructurelle</span>
                    <span class="co-ribbon-item"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 inline-block"></span> Support Continu 24/7</span>
                    <span class="co-ribbon-item"><span class="w-1.5 h-1.5 rounded-full bg-purple-400 inline-block"></span> Innovation Système</span>
                    @endfor
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
         §5 · CTA FINAL
         ═══════════════════════════════════════════════════════════════ --}}
<section class="relative py-36 border-t border-white/[0.04] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-brand-950/20 pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-[350px] bg-brand-500/[0.08] blur-[130px] rounded-t-full pointer-events-none"></div>

    {{-- Animated grid overlay --}}
    <div class="absolute inset-0 co-grid opacity-[0.04] pointer-events-none"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center space-y-10">
        <div class="co-label-pill mx-auto">Prochaine Étape</div>

        <h2 class="text-[clamp(2.4rem,5.5vw,5rem)] font-black text-white leading-[1.0] tracking-[-0.04em]">
            Construisons une<br>
            infrastructure
            <span class="co-gradient-text block mt-1">fiable et performante.</span>
        </h2>

        <p class="text-lg text-slate-400 font-light leading-[1.8] max-w-xl mx-auto">
            Discutons de votre projet et identifions ensemble la solution technique parfaitement adaptée à vos objectifs.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="{{ route('contact') }}" class="co-btn-cta">
                <div class="co-btn-cta-shine"></div>
                <span class="relative z-10">Demander une consultation</span>
                <svg class="w-5 h-5 relative z-10 co-btn-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
            <a href="tel:+237000000000" class="co-btn-ghost">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                Nous appeler
            </a>
        </div>
    </div>
</section>

</div>{{-- /co-page --}}


{{-- ═══════════════════════════════════════════════════════════════════
     STYLES
     ═══════════════════════════════════════════════════════════════════ --}}
<style>
    /* ── Design tokens ─────────────────────────────────────────────────── */
    :root {
        --brand: #1E98C5;
        --green: #9BC53D;
        --ep: cubic-bezier(0.76, 0, 0.24, 1);
        /* premium ease */
        --eo: cubic-bezier(0.16, 1, 0.3, 1);
        /* ease-out spring */
    }

    /* ── Base ──────────────────────────────────────────────────────────── */
    .co-page *,
    .co-page *::before,
    .co-page *::after {
        box-sizing: border-box;
    }

    .co-page h1,
    .co-page h2,
    .co-page h3,
    .co-page h4 {
        font-family: 'Plus Jakarta Sans', 'Syne', system-ui, sans-serif;
    }

    .co-page ::-webkit-scrollbar {
        width: 6px;
    }

    .co-page ::-webkit-scrollbar-track {
        background: #040810;
    }

    .co-page ::-webkit-scrollbar-thumb {
        background: #1e293b;
        border-radius: 3px;
    }

    [x-cloak] {
        display: none !important;
    }

    /* ── Grain ─────────────────────────────────────────────────────────── */
    .co-grain {
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    }

    /* ── Blueprint grid ─────────────────────────────────────────────────── */
    .co-grid {
        background-image:
            linear-gradient(var(--brand) 0.5px, transparent 0.5px),
            linear-gradient(90deg, var(--brand) 0.5px, transparent 0.5px);
        background-size: 60px 60px;
        mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black, transparent);
    }

    /* ── Orb pulse ─────────────────────────────────────────────────────── */
    @keyframes co-orb {

        0%,
        100% {
            opacity: .6;
            transform: scale(1)
        }

        50% {
            opacity: 1;
            transform: scale(1.08)
        }
    }

    .co-orb-pulse {
        animation: co-orb 7s ease-in-out infinite;
    }

    /* ── Animated bars ─────────────────────────────────────────────────── */
    .co-bar {
        display: inline-block;
        width: 2px;
        height: 12px;
        border-radius: 1px;
        animation: co-bars 1s ease-in-out infinite alternate;
    }

    @keyframes co-bars {
        from {
            transform: scaleY(.5)
        }

        to {
            transform: scaleY(1)
        }
    }

    /* ── Fade-up entrance ──────────────────────────────────────────────── */
    @keyframes co-fade-up {
        from {
            opacity: 0;
            transform: translateY(22px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .co-fade-up {
        animation: co-fade-up .65s var(--eo) both;
    }

    /* ── Gradient text ─────────────────────────────────────────────────── */
    .co-gradient-text {
        display: inline;
        background: linear-gradient(135deg, var(--brand) 0%, #3bb3d6 40%, var(--green) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: co-flow 6s linear infinite;
    }

    @keyframes co-flow {
        0% {
            background-position: 0%
        }

        100% {
            background-position: 200%
        }
    }

    /* ── Shimmer text (hero title) ─────────────────────────────────────── */
    @keyframes co-shimmer {
        0% {
            background-position: 0%
        }

        100% {
            background-position: 200%
        }
    }

    .co-shimmer {
        animation: co-shimmer 5s linear infinite;
    }

    /* ── Expand underline ──────────────────────────────────────────────── */
    @keyframes co-expand {
        from {
            transform: scaleX(0)
        }

        to {
            transform: scaleX(1)
        }
    }

    .co-line-reveal {
        transform: scaleX(0);
        animation: co-expand 1s var(--eo) .9s forwards;
    }

    /* ── Label pill ────────────────────────────────────────────────────── */
    .co-label-pill {
        display: inline-flex;
        align-items: center;
        padding: .3rem .85rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, .04);
        border: 1px solid rgba(255, 255, 255, .09);
        font-size: .6rem;
        font-weight: 800;
        letter-spacing: .22em;
        text-transform: uppercase;
        color: var(--brand);
    }

    /* ── Buttons ───────────────────────────────────────────────────────── */
    .co-btn-primary {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: .7rem;
        padding: .9rem 2rem;
        background: var(--brand);
        color: #fff;
        border-radius: 14px;
        font-weight: 700;
        font-size: .88rem;
        text-decoration: none;
        overflow: hidden;
        transition: transform .25s var(--eo), box-shadow .25s;
    }

    .co-btn-primary::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, .18);
        transform: translateX(-110%);
        transition: transform .7s ease;
    }

    .co-btn-primary:hover::before {
        transform: translateX(110%);
    }

    .co-btn-primary:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 0 30px rgba(30, 152, 197, .45);
    }

    .co-btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        padding: .9rem 1.8rem;
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 14px;
        font-weight: 600;
        font-size: .88rem;
        color: #94a3b8;
        text-decoration: none;
        backdrop-filter: blur(8px);
        transition: color .2s, background .2s, border-color .2s;
    }

    .co-btn-ghost:hover {
        color: #fff;
        background: rgba(255, 255, 255, .05);
        border-color: rgba(255, 255, 255, .18);
    }

    .co-btn-cta {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: .75rem;
        padding: 1.05rem 2.5rem;
        background: linear-gradient(135deg, #1478a0, #0e7490);
        color: #fff;
        border-radius: 16px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        overflow: hidden;
        box-shadow: 0 0 40px rgba(30, 152, 197, .25);
        ring: 1px solid rgba(255, 255, 255, .15);
        transition: transform .25s var(--eo), box-shadow .25s;
    }

    .co-btn-cta:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 0 60px rgba(30, 152, 197, .5);
    }

    .co-btn-cta-shine {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .2), transparent);
        transform: translateX(-110%);
        transition: transform .8s ease;
    }

    .co-btn-cta:hover .co-btn-cta-shine {
        transform: translateX(110%);
    }

    .co-btn-arrow {
        transition: transform .2s;
    }

    .co-btn-primary:hover .co-btn-arrow,
    .co-btn-cta:hover .co-btn-arrow {
        transform: translateX(4px);
    }

    /* ═══════════════════════════════════════════════════════════════════
   DOMAIN CARDS (Hero right column)
   ═══════════════════════════════════════════════════════════════════ */
    .co-domain-card {
        position: absolute;
        left: 0;
        right: 0;
        border-radius: 28px;
        background: rgba(255, 255, 255, .025);
        border: 1px solid rgba(255, 255, 255, .07);
        backdrop-filter: blur(20px);
        overflow: hidden;
        cursor: pointer;

        /* Default (inactive) state */
        bottom: 56px;
        height: 90px;
        opacity: .35;
        filter: grayscale(.25);
        transform: scale(.9);
        z-index: 5;

        transition:
            bottom .75s var(--ep),
            height .75s var(--ep),
            opacity .55s var(--ep),
            transform .75s var(--ep),
            filter .55s var(--ep),
            border-color .55s var(--ep),
            box-shadow .55s var(--ep),
            background .55s var(--ep);
    }

    /* Stagger inactive cards  */
    .co-domain-card:nth-child(2) {
        left: 0;
        right: auto;
        width: 30%;
        z-index: 6;
    }

    .co-domain-card:nth-child(3) {
        left: 50%;
        transform: translateX(-50%) scale(.9);
        width: 30%;
        z-index: 6;
    }

    .co-domain-card:nth-child(4) {
        right: 0;
        left: auto;
        width: 30%;
        z-index: 6;
    }

    /* Active state */
    .co-domain-card.is-active {
        bottom: 120px;
        height: 340px;
        opacity: 1;
        filter: grayscale(0);
        transform: scale(1);
        z-index: 20;
        left: 0;
        right: 0;
        width: 100%;
        border-color: rgba(var(--card-color-rgb, 30, 152, 197), .35);
        box-shadow: 0 0 60px color-mix(in srgb, var(--card-color) 15%, transparent),
            inset 0 0 50px color-mix(in srgb, var(--card-color) 4%, transparent);
        background: rgba(255, 255, 255, .04);
    }

    /* Scan line */
    .co-scan {
        position: absolute;
        top: 0;
        left: -60%;
        width: 50%;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--card-color, var(--brand)), transparent);
        opacity: 0;
        pointer-events: none;
    }

    .co-domain-card.is-active .co-scan {
        opacity: 1;
        animation: co-scan 3s linear infinite;
    }

    @keyframes co-scan {
        from {
            left: -50%
        }

        to {
            left: 130%
        }
    }

    /* Icon */
    .co-domain-icon {
        position: absolute;
        top: 1.1rem;
        left: 1.1rem;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, .6);
        transition: width .65s var(--ep), height .65s var(--ep),
            border-radius .65s var(--ep), background .55s, color .55s;
    }

    .co-domain-icon svg {
        width: 16px;
        height: 16px;
        transition: width .65s var(--ep), height .65s var(--ep);
    }

    .co-domain-card.is-active .co-domain-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        background: var(--card-color);
        color: #fff;
        top: 1.75rem;
        left: 1.75rem;
        box-shadow: 0 6px 20px color-mix(in srgb, var(--card-color) 40%, transparent);
    }

    .co-domain-card.is-active .co-domain-icon svg {
        width: 24px;
        height: 24px;
    }

    /* Body */
    .co-domain-body {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem 1.1rem;
        transition: padding .65s var(--ep);
    }

    .co-domain-card.is-active .co-domain-body {
        padding: 1.75rem;
    }

    .co-domain-sub {
        font-size: .58rem;
        font-weight: 800;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .3);
        margin-bottom: .35rem;
        transition: color .45s;
    }

    .co-domain-card.is-active .co-domain-sub {
        color: color-mix(in srgb, var(--card-color) 80%, white);
    }

    .co-domain-title {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        font-weight: 800;
        color: rgba(255, 255, 255, .7);
        font-size: .72rem;
        letter-spacing: .04em;
        text-transform: uppercase;
        transition: font-size .65s var(--ep), color .45s, letter-spacing .55s;
        line-height: 1.1;
    }

    .co-domain-card.is-active .co-domain-title {
        font-size: 1.6rem;
        color: #fff;
        letter-spacing: -.03em;
        text-transform: none;
        margin-bottom: .9rem;
    }

    .co-domain-desc {
        font-size: .82rem;
        color: #64748b;
        font-weight: 300;
        line-height: 1.7;
    }

    /* Progress bar */
    .co-domain-progress {
        position: absolute;
        bottom: 0;
        left: 1.75rem;
        right: 1.75rem;
        height: 2px;
        background: rgba(255, 255, 255, .07);
        border-radius: 1px;
        overflow: hidden;
    }

    .co-progress-fill {
        height: 100%;
        background: var(--card-color, var(--brand));
        border-radius: 1px;
        animation: co-prog 6s linear forwards;
    }

    @keyframes co-prog {
        from {
            width: 0%
        }

        to {
            width: 100%
        }
    }

    /* Status pill */
    .co-domain-status {
        position: absolute;
        top: 1.75rem;
        right: 1.75rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .co-pulse-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--card-color, var(--brand));
        animation: co-pulse-dot 2s ease-in-out infinite;
    }

    @keyframes co-pulse-dot {

        0%,
        100% {
            box-shadow: 0 0 0 0 color-mix(in srgb, var(--card-color, var(--brand)) 50%, transparent)
        }

        50% {
            box-shadow: 0 0 0 5px color-mix(in srgb, var(--card-color, var(--brand)) 0%, transparent)
        }
    }

    /* Nav dots */
    .co-nav-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .18);
        border: none;
        padding: 0;
        cursor: pointer;
        transition: background .35s, transform .35s var(--eo), width .35s var(--eo);
    }

    .co-nav-dot.is-active {
        background: var(--brand);
        transform: scale(1.7);
    }

    /* ═══════════════════════════════════════════════════════════════════
   SERVICE CARDS
   ═══════════════════════════════════════════════════════════════════ */
    .co-service-card {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 2.25rem;
        background: rgba(255, 255, 255, .02);
        border: 1px solid rgba(255, 255, 255, .06);
        border-radius: 24px;
        backdrop-filter: blur(12px);
        overflow: hidden;
        transition: transform .35s var(--eo), background .35s, border-color .35s, box-shadow .35s;
    }

    .co-service-card:hover {
        transform: translateY(-6px);
        background: rgba(255, 255, 255, .03);
        border-color: rgba(30, 152, 197, .38);
        box-shadow: 0 24px 48px -24px rgba(30, 152, 197, .2);
    }

    .co-arrow-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, .1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ═══════════════════════════════════════════════════════════════════
   MATRIX ROWS
   ═══════════════════════════════════════════════════════════════════ */
    .co-matrix-row {
        position: relative;
        background: rgba(255, 255, 255, .02);
        border: 1px solid rgba(255, 255, 255, .06);
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        transition: background .4s, border-color .4s;
    }

    .co-matrix-row:hover {
        background: rgba(255, 255, 255, .035);
        border-color: color-mix(in srgb, var(--row-color) 30%, transparent);
    }

    .co-matrix-icon {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--row-color) 12%, transparent);
        border: 1px solid color-mix(in srgb, var(--row-color) 22%, transparent);
        color: var(--row-color);
        transition: transform .4s var(--eo);
    }

    .co-matrix-row:hover .co-matrix-icon {
        transform: scale(1.1);
    }

    .co-partner-tag {
        padding: .35rem 1rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, .04);
        border: 1px solid rgba(255, 255, 255, .08);
        font-size: .72rem;
        font-weight: 700;
        color: #64748b;
        white-space: nowrap;
        transition: color .25s, border-color .25s, background .25s;
    }

    .co-matrix-row:hover .co-partner-tag {
        color: #cbd5e1;
        border-color: color-mix(in srgb, var(--row-color) 25%, transparent);
        background: color-mix(in srgb, var(--row-color) 6%, transparent);
    }

    /* ═══════════════════════════════════════════════════════════════════
   SCROLLING RIBBON
   ═══════════════════════════════════════════════════════════════════ */
    .co-ribbon-track {
        display: flex;
        gap: 3rem;
        width: max-content;
        animation: co-ribbon 35s linear infinite;
    }

    .co-ribbon-track:hover {
        animation-play-state: paused;
    }

    .co-ribbon-item {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        font-size: .6rem;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .35em;
        color: #334155;
        white-space: nowrap;
    }

    @keyframes co-ribbon {
        0% {
            transform: translateX(0)
        }

        100% {
            transform: translateX(-50%)
        }
    }
</style>

@endsection