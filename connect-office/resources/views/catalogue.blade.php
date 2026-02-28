@extends('layouts.frontend')

@section('content')
<!-- Polices Tech -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

<div class="bg-[#010409] text-slate-200 font-sans antialiased overflow-x-hidden relative italic">
    <div class="fixed inset-0 z-[0] opacity-[0.03] pointer-events-none bg-grain mix-blend-overlay"></div>

    <!-- ========================================= -->
    <!-- 1. HERO : MATRICE TECHNOLOGIQUE -->
    <!-- ========================================= -->
    <header class="relative min-h-[70vh] border-b border-white/5 overflow-hidden flex flex-col items-center justify-center">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-[#1E98C5]/10 via-[#010409] to-[#010409]"></div>
        
        <div class="relative z-10 max-w-5xl mx-auto px-6 text-center space-y-8 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1E98C5]/10 border border-[#1E98C5]/20 text-[#1E98C5] text-[10px] font-mono uppercase tracking-[0.2em] mb-4">
                <span class="w-2 h-2 rounded-full bg-[#1E98C5] animate-pulse"></span>
                Engineering Reference Index v4.0
            </div>
            <h1 class="text-6xl md:text-8xl font-black text-white leading-[0.9] tracking-tighter uppercase italic">
                Référentiel <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1E98C5] via-white to-[#9BC53D] animate-gradient-flow bg-[length:200%_auto]">Constructeurs.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-[10px] uppercase tracking-[0.4em] text-slate-500 font-bold leading-relaxed">
                Topologies hardware et partenaires technologiques certifiés pour infrastructures critiques.
            </p>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
            <div class="w-[1px] h-20 bg-gradient-to-b from-[#1E98C5] to-transparent"></div>
        </div>
    </header>

    <div class="max-w-[1440px] mx-auto px-6 py-20 flex flex-col lg:flex-row gap-16">
        
        <!-- SIDEBAR : NAVIGATION PAR RUBRIQUE -->
        <aside class="lg:w-72 shrink-0">
            <div class="sticky top-32 space-y-12">
                <nav class="flex flex-col gap-8">
                    <h3 class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-600 italic">Domaines d'expertise</h3>
                    <div class="flex flex-col gap-4">
                        <a href="#rubrique-telecom" class="nav-link text-[12px] font-black text-white uppercase tracking-widest flex items-center gap-3 group">
                            <span class="w-2 h-2 bg-[#1E98C5] rounded-full group-hover:scale-150 transition-all"></span>
                            Réseaux & Télécoms
                        </a>
                        <a href="#rubrique-security" class="nav-link text-[12px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-3 group hover:text-white transition-all">
                            <span class="w-2 h-2 bg-slate-800 rounded-full group-hover:bg-[#9BC53D] transition-all"></span>
                            Sécurité Électronique
                        </a>
                        <a href="#rubrique-energy" class="nav-link text-[12px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-3 group hover:text-white transition-all">
                            <span class="w-2 h-2 bg-slate-800 rounded-full group-hover:bg-[#9BC53D] transition-all"></span>
                            Énergie & Solaire
                        </a>
                        <a href="#rubrique-gtb" class="nav-link text-[12px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-3 group hover:text-white transition-all">
                            <span class="w-2 h-2 bg-slate-800 rounded-full group-hover:bg-[#9BC53D] transition-all"></span>
                            GTB & Domotique
                        </a>
                    </div>
                </nav>

                <div class="p-8 rounded-[2rem] bg-white/[0.02] border border-white/5 relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-[#9BC53D]"></div>
                    <p class="text-[10px] font-black text-white uppercase tracking-widest mb-4">Standard Qualité</p>
                    <p class="text-[11px] text-slate-500 leading-relaxed italic">Toutes nos références respectent les normes ISO et IEEE pour une interopérabilité totale.</p>
                </div>
            </div>
        </aside>

        <!-- CONTENU : LA MATRICE -->
        <div class="flex-1 space-y-32">

            <!-- 1. RÉSEAUX & TÉLÉCOMS -->
            <section id="rubrique-telecom" class="scroll-mt-32 space-y-12">
                <div class="space-y-4">
                    <h2 class="text-5xl font-black text-white uppercase italic tracking-tighter leading-none">01. Réseaux & Télécoms</h2>
                    <p class="text-brand-400 font-mono text-[10px] uppercase tracking-[0.3em]">Infrastructure Backbone & Wireless Matrix</p>
                </div>

                <div class="grid gap-6">
                    <!-- Sous-Rubrique : Infrastructure Lan -->
                    <div class="p-10 rounded-[3rem] bg-white/[0.02] border border-white/5 group hover:border-[#1E98C5]/30 transition-all duration-500">
                        <div class="flex flex-col md:flex-row justify-between gap-10">
                            <div class="md:w-1/2 space-y-6">
                                <h3 class="text-xl font-black text-white uppercase italic leading-none">Infrastructure LAN & Ondulé</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Conception de câblage structuré, déploiement de réseaux ondulés sécurisés pour parcs informatiques critiques.</p>
                                <ul class="space-y-2">
                                    <li class="flex items-center gap-3 text-[10px] font-bold text-[#9BC53D] uppercase tracking-widest">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Audit & Certification Fluke
                                    </li>
                                </ul>
                            </div>
                            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                                <div class="reference-badge">CommScope (Systimax)</div>
                                <div class="reference-badge">Legrand (LCS3)</div>
                                <div class="reference-badge">Nexans</div>
                                <div class="reference-badge">Schneider Electric</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sous-Rubrique : Switching -->
                    <div class="p-10 rounded-[3rem] bg-white/[0.02] border border-white/5 group hover:border-[#1E98C5]/30 transition-all duration-500">
                        <div class="flex flex-col md:flex-row justify-between gap-10">
                            <div class="md:w-1/2 space-y-6">
                                <h3 class="text-xl font-black text-white uppercase italic leading-none">Switching, Routing & WiFi Pro</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Topologies Core & Access, segmentation VLAN, et WiFi haute densité pour campus et sièges sociaux.</p>
                            </div>
                            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                                <div class="reference-badge">Cisco Systems</div>
                                <div class="reference-badge">Ubiquiti (UniFi)</div>
                                <div class="reference-badge">HPE Aruba</div>
                                <div class="reference-badge">MikroTik</div>
                            </div>
                        </div>
                    </div>

                    <!-- Sous-Rubrique : Télécom Radio -->
                    <div class="p-10 rounded-[3rem] bg-white/[0.02] border border-white/5 group hover:border-[#1E98C5]/30 transition-all duration-500">
                        <div class="flex flex-col md:flex-row justify-between gap-10">
                            <div class="md:w-1/2 space-y-6">
                                <h3 class="text-xl font-black text-white uppercase italic leading-none">Liaisons Sans-Fil & Radio</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Interconnexion Point-à-Point (PTP) et Multipoint. Flotte de Talkie-Walkies longue portée (DMR/Analogique).</p>
                            </div>
                            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                                <div class="reference-badge">Motorola Solutions</div>
                                <div class="reference-badge">Hytera</div>
                                <div class="reference-badge">Cambium Networks</div>
                                <div class="reference-badge">Mimosa (Airspan)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. SÉCURITÉ ÉLECTRONIQUE -->
            <section id="rubrique-security" class="scroll-mt-32 space-y-12">
                <div class="space-y-4">
                    <h2 class="text-5xl font-black text-white uppercase italic tracking-tighter leading-none">02. Sécurité Électronique</h2>
                    <p class="text-[#9BC53D] font-mono text-[10px] uppercase tracking-[0.3em]">Surveillance, Biometrics & Fire Protection</p>
                </div>

                <div class="grid gap-6">
                    <!-- Vidéosurveillance -->
                    <div class="p-10 rounded-[3rem] bg-white/[0.02] border border-white/5 group hover:border-[#9BC53D]/30 transition-all duration-500">
                        <div class="flex flex-col md:flex-row justify-between gap-10">
                            <div class="md:w-1/2 space-y-6">
                                <h3 class="text-xl font-black text-white uppercase italic leading-none">Surveillance & IA Vidéo</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Lecture de plaques (LPR), reconnaissance faciale, et analyse comportementale.</p>
                            </div>
                            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                                <div class="reference-badge">Hikvision (ColorVu)</div>
                                <div class="reference-badge">Dahua Technology</div>
                                <div class="reference-badge">Axis Communications</div>
                                <div class="reference-badge">Milestone (VMS)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Accès & Incendie -->
                    <div class="p-10 rounded-[3rem] bg-white/[0.02] border border-white/5 group hover:border-[#9BC53D]/30 transition-all duration-500">
                        <div class="flex flex-col md:flex-row justify-between gap-10">
                            <div class="md:w-1/2 space-y-6">
                                <h3 class="text-xl font-black text-white uppercase italic leading-none">Accès, Alarme & Incendie</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Contrôle d'accès biométrique, détection intrusion périmétrique et SSI (Système de Sécurité Incendie).</p>
                            </div>
                            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                                <div class="reference-badge">ZKTeco</div>
                                <div class="reference-badge">Suprema</div>
                                <div class="reference-badge">Honeywell</div>
                                <div class="reference-badge">Bosch Security</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. ÉNERGIE & SOLAIRE -->
            <section id="rubrique-energy" class="scroll-mt-32 space-y-12">
                <div class="space-y-4">
                    <h2 class="text-5xl font-black text-white uppercase italic tracking-tighter leading-none">03. Énergie & Solaire</h2>
                    <p class="text-white font-mono text-[10px] uppercase tracking-[0.3em]">Critical Power & Hybrid Solar Solutions</p>
                </div>

                <div class="grid gap-6">
                    <div class="p-10 rounded-[3rem] bg-white/[0.02] border border-white/5 group hover:border-white/20 transition-all duration-500">
                        <div class="flex flex-col md:flex-row justify-between gap-10">
                            <div class="md:w-1/2 space-y-6">
                                <h3 class="text-xl font-black text-white uppercase italic leading-none">Autonomie Énergétique</h3>
                                <p class="text-sm text-slate-400 leading-relaxed">Onduleurs de grande capacité (10KVA+), stockage Lithium-Ion et parcs photovoltaïques industriels.</p>
                            </div>
                            <div class="md:w-1/2 grid grid-cols-2 gap-4">
                                <div class="reference-badge">Victron Energy</div>
                                <div class="reference-badge">Huawei Solar</div>
                                <div class="reference-badge">SMA</div>
                                <div class="reference-badge">Eaton (UPS)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <!-- ========================================= -->
    <!-- SECTION CONTACT : INITIALISER UN PROJET -->
    <!-- ========================================= -->
    <section class="relative py-40 border-t border-white/5">
        <div class="absolute inset-x-0 bottom-0 h-full bg-[radial-gradient(circle_at_bottom,_var(--tw-gradient-stops))] from-[#1E98C5]/10 via-[#010409] to-[#010409]"></div>
        
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10 space-y-12">
            <h2 class="text-5xl md:text-7xl font-black text-white tracking-tighter uppercase italic leading-none">
                Une architecture <br> <span class="text-[#1E98C5]">sur-mesure ?</span>
            </h2>
            <p class="text-slate-400 font-light max-w-xl mx-auto italic leading-relaxed">
                Nos ingénieurs certifiés conçoivent votre topologie réseau et sécurité en utilisant ces références constructeurs.
            </p>
            <div class="flex justify-center pt-8">
                <a href="{{ route('contact') }}" class="group relative px-12 py-6 bg-white text-[#010409] font-black text-xs uppercase tracking-[0.4em] rounded-2xl transition-all hover:scale-105 active:scale-95 overflow-hidden">
                    <span class="relative z-10">Lancer l'audit technique</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-[#1E98C5] to-[#9BC53D] opacity-0 group-hover:opacity-10 transition-opacity"></div>
                </a>
            </div>
        </div>
    </section>
</div>

<style>
    /* STYLES DES BADGES CONSTRUCTEURS */
    .reference-badge {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 1rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-[10px] font-black uppercase tracking-widest text-slate-400 transition-all duration-300;
        cursor: default;
    }
    .group:hover .reference-badge {
        border-color: rgba(255, 255, 255, 0.2);
        color: white;
        background: rgba(255, 255, 255, 0.05);
    }

    /* ANIMATIONS */
    @keyframes gradient-flow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradient-flow { background-size: 200% 200%; animation: gradient-flow 4s ease infinite; }
    
    .nav-link { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .nav-link:hover { transform: translateX(10px); color: white !important; }

    html { scroll-behavior: smooth; }

    /* SCROLLBAR */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #010409; }
    ::-webkit-scrollbar-thumb { background: #1E98C5; }
</style>
@endsection