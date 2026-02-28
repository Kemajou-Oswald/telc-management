@extends('layouts.frontend')

@section('title', 'Contact | Connect Office')

@section('content')

<div class="bg-[#000205] text-slate-200 min-h-screen font-sans antialiased overflow-x-hidden pt-20">

    <!-- FOND TECHNIQUE ANIMÉ -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-brand-blue/10 blur-[120px] rounded-full animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-brand-green/10 blur-[120px] rounded-full animate-pulse-slow" style="animation-delay: 3s"></div>
    </div>

    <section class="relative z-10 max-w-7xl mx-auto px-6 py-12">

        <!-- HEADER : TITRE XXL AVEC REVEAL ANIMATION -->
        <div class="mb-24">
            <div class="reveal-text inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-green opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-green"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-brand-green">Système Connecté : Online</span>
            </div>

            <h1 class="text-6xl md:text-8xl font-black tracking-tight mb-8 leading-[0.9]">

                <span class="block text-white opacity-90">
                    UN PROJET
                </span>

                <span class="relative inline-block mt-4">

                    <span class="text-transparent bg-clip-text 
                     bg-gradient-to-r from-[#1E98C5] via-white to-[#9BC53D] 
                     bg-[length:200%_auto] animate-gradient-flow">
                        D'INGÉNIERIE ?
                    </span>

                    <!-- Ligne animée -->
                    <span class="absolute bottom-0 left-0 w-full h-[2px] 
                     bg-gradient-to-r from-[#1E98C5] to-transparent 
                     animate-line-reveal">
                    </span>

                </span>

            </h1>


            <p class="max-w-2xl text-xl text-slate-400 font-medium leading-relaxed reveal-text" style="animation-delay: 0.4s">
                Ne vous contentez pas de nous contacter. Donnons vie à votre infrastructure avec l'expertise Connect Office.
            </p>
        </div>

        <div class="grid lg:grid-cols-12 gap-12">

            <!-- COLONNE GAUCHE : LE FORMULAIRE HAUTE-PRÉCISION -->
            <div class="lg:col-span-7 animate-slide-up" style="animation-delay: 0.6s">
                <div class="relative bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[3rem] p-8 md:p-12 shadow-2xl group hover:border-brand-blue/50 transition-all duration-700">

                    <h2 class="text-2xl font-black text-white mb-10 flex items-center gap-4 uppercase tracking-widest">
                        <svg class="w-8 h-8 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.5 2.5 0 113.536 3.536L12 20.414H8v-4.144L18.586 3.586z" />
                        </svg>
                        Transmission de données
                    </h2>

                    <form action="{{ route('quote.store') }}" method="POST" class="space-y-10">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-10">
                            <div class="relative group/field">
                                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 group-focus-within/field:text-brand-blue transition-colors">Nom Complet</label>
                                <input type="text" name="name" required
                                    class="w-full bg-transparent border-b border-white/20 py-4 text-white 
focus:border-[#1E98C5] focus:outline-none 
placeholder:text-slate-500
transition-all duration-300 font-semibold tracking-wide">
                            </div>
                            <div class="relative group/field">
                                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 group-focus-within/field:text-brand-blue transition-colors">Email Professionnel</label>
                                <input type="email" name="email" required class="w-full bg-transparent border-b border-white/20 py-4 text-white 
focus:border-[#1E98C5] focus:outline-none 
placeholder:text-slate-500
transition-all duration-300 font-semibold tracking-wide">
                            </div>
                        </div>

                        <div class="relative group/field">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 group-focus-within/field:text-brand-green transition-colors">Objet de la demande</label>
                            <input type="text" name="subject" class="w-full bg-transparent border-b border-white/20 py-4 text-white 
focus:border-[#1E98C5] focus:outline-none 
placeholder:text-slate-500
transition-all duration-300 font-semibold tracking-wide">
                        </div>

                        <div class="relative group/field">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 group-focus-within/field:text-brand-blue transition-colors">Message</label>
                            <textarea name="message" rows="4" required class="w-full bg-white/5 border-2 border-white/10 rounded-2xl p-6 text-white focus:border-brand-blue focus:outline-none transition-all font-medium mt-2"></textarea>
                        </div>

                        <button type="submit"
                            class="relative w-full py-6 rounded-2xl font-bold text-sm uppercase tracking-[0.3em]
overflow-hidden transition-all duration-500 group active:scale-95">

                            <!-- Background animé -->
                            <span class="absolute inset-0 bg-gradient-to-r 
                 from-[#1E98C5] via-[#3bb3d6] to-[#9BC53D] 
                 bg-[length:200%_auto] 
                 animate-gradient-flow
                 transition-all duration-700 
                 group-hover:brightness-110">
                            </span>

                            <!-- Glow -->
                            <span class="absolute -inset-1 rounded-2xl 
                 bg-gradient-to-r from-[#1E98C5] to-[#9BC53D] 
                 blur-xl opacity-30 
                 group-hover:opacity-60 
                 transition-all duration-500">
                            </span>

                            <!-- Contenu -->
                            <span class="relative z-10 flex items-center justify-center gap-4 text-white">

                                Envoyer la requête

                                <svg class="w-5 h-5 transition-all duration-500 
                    group-hover:translate-x-2 
                    group-hover:scale-110"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>

                            </span>

                        </button>


                    </form>
                </div>
            </div>

            <!-- COLONNE DROITE : CONTACTS INTERACTIFS -->
            <div class="lg:col-span-5 space-y-6">

                <!-- WHATSAPP CUSTOM -->
                @php
                $waMessage = rawurlencode("Bonjour Connect Office, je souhaite discuter d'un projet technique.");
                @endphp
                <a href="https://wa.me/237690585003?text={{ $waMessage }}" target="_blank"
                    class="animate-slide-right flex items-center gap-6 p-8 bg-white/5 border border-white/10 rounded-[2.5rem] hover:bg-brand-green/10 hover:border-brand-green/50 transition-all duration-500 group shadow-lg" style="animation-delay: 0.8s">
                    <div class="w-16 h-16 bg-slate-900 border border-brand-green/30 rounded-2xl flex items-center justify-center text-brand-green group-hover:shadow-[0_0_30px_rgba(125,187,66,0.4)] transition-all">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-brand-green tracking-widest mb-1">WhatsApp Direct</p>
                        <h4 class="text-xl font-black text-white">+237 690 58 50 03</h4>
                        <p class="text-xs text-slate-500 font-bold mt-1 uppercase">Démarrer une discussion →</p>
                    </div>
                </a>

                <!-- EMAIL CUSTOM -->
                @php
                $mailSubject = rawurlencode("Demande de renseignements - Connect Office");
                @endphp
                <a href="mailto:contact@connect-office.cm?subject={{ $mailSubject }}"
                    class="animate-slide-right flex items-center gap-6 p-8 bg-white/5 border border-white/10 rounded-[2.5rem] hover:bg-brand-blue/10 hover:border-brand-blue/50 transition-all duration-500 group shadow-lg" style="animation-delay: 1s">
                    <div class="w-16 h-16 bg-slate-900 border border-brand-blue/30 rounded-2xl flex items-center justify-center text-brand-blue group-hover:shadow-[0_0_30px_rgba(30,152,197,0.4)] transition-all">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-brand-blue tracking-widest mb-1">Email Officiel</p>
                        <h4 class="text-xl font-black text-white">contact@connect-office.cm</h4>
                        <p class="text-xs text-slate-500 font-bold mt-1 uppercase">Envoyer un dossier →</p>
                    </div>
                </a>

                <!-- CARTE DE RÉFÉRENCE -->
                <div class="animate-slide-right bg-gradient-to-br from-slate-900 to-black p-10 rounded-[3rem] border border-white/5 relative overflow-hidden" style="animation-delay: 1.2s">
                    <div class="relative z-10">
                        <h4 class="text-2xl font-black text-white mb-6 tracking-tight">Connect Office</h4>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium">
                            Solutions intégrées en <span class="text-brand-green font-bold">Sécurité Électronique</span>, <span class="text-brand-blue font-bold">Réseaux</span> & <span class="text-white font-bold">Énergies</span>. Notre bureau d'études traite vos demandes sous 24h ouvrées.
                        </p>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brand-green/5 rounded-full blur-3xl"></div>
                </div>

            </div>
        </div>
    </section>
</div>

<style>
    /* MOTEUR D'ANIMATION XXL */
    @keyframes revealText {
        0% {
            opacity: 0;
            transform: translateY(50px);
            filter: blur(10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }
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
    }

    @keyframes slideUp {
        0% {
            opacity: 0;
            transform: translateY(100px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideRight {
        0% {
            opacity: 0;
            transform: translateX(50px);
        }

        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes gradientFlow {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .reveal-text {
        animation: revealText 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-slide-up {
        animation: slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .animate-slide-right {
        animation: slideRight 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .animate-gradient-flow {
        animation: gradientFlow 5s linear infinite;
    }

    .animate-pulse-slow {
        animation: pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Fix pour le décalage du titre */
    h1 {
        display: block;
        width: 100%;
        text-align: left;
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 3.5rem;
            text-align: center;
        }
    }
</style>

@endsection