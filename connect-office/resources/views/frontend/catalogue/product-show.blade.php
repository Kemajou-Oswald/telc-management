@extends('layouts.frontend')

@section('content')
<!-- Polices Tech & Style -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700&family=Inter:wght@300;400;700;900&display=swap" rel="stylesheet">

<style>
    .font-tech { font-family: 'Space Grotesk', sans-serif; }
    .bg-tech-grid { background-image: radial-gradient(rgba(30, 152, 197, 0.1) 1px, transparent 1px); background-size: 40px 40px; }
    .glass-effect { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(15px); border: 1px solid rgba(0,0,0,0.05); }
    
    @keyframes gradient-flow { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    .animate-gradient-flow { background-size: 200% 200%; animation: gradient-flow 4s ease infinite; }
</style>

<div class="bg-[#010409] text-slate-200 min-h-screen font-['Inter'] antialiased overflow-x-hidden italic">
    
    <div class="fixed inset-0 z-[0] opacity-[0.03] pointer-events-none bg-grain mix-blend-overlay"></div>

    <div class="max-w-[1400px] mx-auto px-6 py-12 lg:py-24 relative z-10">

        <!-- BREADCRUMB -->
        <nav class="flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.4em] text-slate-500 mb-16 italic">
            <a href="{{ route('catalogue') }}" class="hover:text-[#1E98C5] transition-colors uppercase">Catalogue</a>
            <span class="text-[#9BC53D]">/</span>
            <span class="text-white">{{ $product->category->name ?? 'Infrastructure' }}</span>
        </nav>

        <!-- ================= 1. HERO PRODUIT ================= -->
        <div class="grid lg:grid-cols-12 gap-16 lg:gap-24 items-center">
            <div class="lg:col-span-7 relative group">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-[#1E98C5]/10 via-transparent to-transparent blur-3xl opacity-50"></div>
                <div class="relative glass-effect rounded-[3rem] p-12 overflow-hidden flex items-center justify-center min-h-[500px]">
                    <div class="absolute inset-0 opacity-[0.05]" style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 40px 40px;"></div>
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="relative z-10 w-full h-auto max-h-[450px] object-contain transform group-hover:scale-105 transition-transform duration-1000">
                    @endif
                    <div class="absolute bottom-8 left-8">
                        <div class="px-3 py-1 bg-black/50 backdrop-blur-md border border-white/10 rounded-lg text-[9px] font-mono text-white tracking-widest uppercase italic">
                            Status: <span class="text-[#9BC53D]">Elite_Unit_v2.6</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-10">
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="h-[1px] w-12 bg-[#1E98C5]"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.5em] text-[#1E98C5] italic">Engineering Hardware</span>
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-black text-white leading-[0.9] tracking-tighter uppercase italic italic">
                        {{ $product->name }}
                    </h1>
                    <p class="text-lg text-slate-400 font-light leading-relaxed border-l-2 border-[#1E98C5]/30 pl-6 italic">
                        {{ $product->short_description ?? 'Architecture critique certifiée pour environnements haute performance.' }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-6 pt-4">
                    <a href="#devis" class="flex-1">
                        <button class="group relative w-full h-[75px] rounded-2xl font-black text-xs uppercase tracking-[0.5em] overflow-hidden transition-all duration-500 active:scale-95 italic">
                            <span class="absolute inset-0 bg-gradient-to-r from-[#1E98C5] via-[#3bb3d6] to-[#9BC53D] animate-gradient-flow italic"></span>
                            <span class="relative z-10 flex items-center justify-center gap-4 text-white italic">
                                Configurer l'unité
                                <svg class="w-6 h-6 transition-transform group-hover:translate-x-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                        </button>
                    </a>
                    @if($product->pdf)
                    <a href="{{ asset('storage/'.$product->pdf) }}" target="_blank"
                       class="px-10 flex items-center justify-center rounded-2xl border border-white/10 hover:bg-white/5 transition-all text-[10px] font-black uppercase tracking-widest text-white italic">
                        DataSheet
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= 2. ANALYSE TECHNIQUE ================= -->
        <div class="mt-40 grid lg:grid-cols-12 gap-16 lg:gap-32">
            <div class="lg:col-span-7 space-y-10">
                <div class="flex items-center gap-6 mb-12 italic">
                    <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">Analyse Technique</h2>
                    <div class="h-[1px] flex-1 bg-gradient-to-r from-white/10 to-transparent italic"></div>
                </div>
                <div class="prose prose-invert max-w-none text-slate-400 text-lg leading-loose italic font-medium italic">
                    {{ $product->description }}
                </div>
            </div>

            <div class="lg:col-span-5 italic">
                <div class="glass-effect rounded-[2.5rem] p-10 relative overflow-hidden italic">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-500 mb-10 italic italic">Technical_Specs_Index</h3>
                    <div class="space-y-6">
                        @foreach(explode("\n", $product->specs) as $spec)
                        @if(trim($spec))
                        <div class="group flex flex-col gap-2 border-b border-white/5 pb-4 last:border-0">
                            <span class="text-[9px] font-bold text-[#1E98C5] uppercase tracking-widest opacity-60 group-hover:opacity-100 transition-opacity italic italic italic">Parameter</span>
                            <span class="text-white font-bold text-sm tracking-wide italic italic italic">{{ $spec }}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= 3. CONVERSION (FORMULAIRE CORRIGÉ) ================= -->
        <div id="devis" class="mt-40 relative py-32 bg-tech-grid border-t border-white/5 italic italic italic">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center relative z-10 italic italic italic">

                <!-- TEXTE GAUCHE -->
                <div class="space-y-8 italic italic">
                    <div class="w-16 h-1 bg-[#9BC53D]"></div>
                    <h2 class="text-5xl md:text-7xl font-black text-white tracking-tighter uppercase italic leading-[0.9] italic italic">
                        Lancer une <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1E98C5] to-white italic italic italic">Étude Projet.</span>
                    </h2>
                    <p class="text-lg text-slate-400 font-light leading-relaxed italic italic italic">Nos ingénieurs analysent votre topologie sous 24h.</p>
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10 w-fit flex items-center gap-4 italic italic italic italic">
                        <span class="w-3 h-3 rounded-full bg-[#9BC53D] animate-pulse shadow-[0_0_10px_#9BC53D]"></span>
                        <span class="text-[10px] font-mono text-white tracking-widest uppercase italic italic italic italic italic">Expert_Engineers: ACTIVE</span>
                    </div>
                </div>

                <!-- FORMULAIRE CORRIGÉ -->
                <div class="italic italic">
                    <div class="p-10 lg:p-14 rounded-[3.5rem] bg-white/[0.03] border border-white/10 backdrop-blur-xl italic italic">
                        
                        @if(session('success'))
                        <div class="bg-[#9BC53D]/10 border border-[#9BC53D] text-[#9BC53D] p-6 rounded-2xl mb-10 font-bold italic italic">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('quote.store') }}" class="relative z-10 space-y-8 italic italic italic">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="type" value="product_specific">

                            <div class="grid md:grid-cols-2 gap-8 italic italic">
                                <div class="space-y-3 italic">
                                    <label class="text-[9px] font-black uppercase text-slate-600 ml-4 tracking-widest italic italic italic">Nom complet</label>
                                    <input type="text" name="name" placeholder="JEAN DUPONT" required 
                                           class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-8 py-5 text-white focus:border-[#1E98C5] outline-none font-bold italic italic text-xs">
                                </div>
                                <div class="space-y-3 italic italic italic">
                                    <label class="text-[9px] font-black uppercase text-slate-600 ml-4 tracking-widest italic italic italic">Email professionnel</label>
                                    <input type="email" name="email" placeholder="PRO@DOMAINE.COM" required 
                                           class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-8 py-5 text-white focus:border-[#1E98C5] outline-none font-bold italic italic text-xs">
                                </div>
                            </div>

                            <div class="space-y-3 italic italic italic italic">
                                <label class="text-[9px] font-black uppercase text-slate-600 ml-4 tracking-widest italic italic italic italic italic">Téléphone</label>
                                <input type="text" name="phone" placeholder="+237 ..." required 
                                       class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-8 py-5 text-white focus:border-[#1E98C5] outline-none font-bold italic italic text-xs">
                            </div>

                            <div class="space-y-3 italic italic italic italic italic italic">
                                <label class="text-[9px] font-black uppercase text-slate-600 ml-4 tracking-widest italic italic italic italic italic italic italic">Votre configuration technique</label>
                                <textarea name="message" rows="5" placeholder="DÉCRIVEZ VOTRE BESOIN..." required 
                                          class="w-full bg-white/[0.03] border border-white/10 rounded-2xl px-8 py-5 text-white focus:border-[#1E98C5] outline-none font-bold italic italic text-xs"></textarea>
                            </div>

                            <button type="submit" class="group relative w-full h-[80px] rounded-2xl font-black text-xs uppercase tracking-[0.5em] overflow-hidden transition-all duration-500 active:scale-95 italic italic italic">
                                <span class="absolute inset-0 bg-gradient-to-r from-[#1E98C5] via-[#3bb3d6] to-[#9BC53D] animate-gradient-flow italic italic italic italic"></span>
                                <span class="relative z-10 flex items-center justify-center gap-4 text-white italic italic italic italic italic">
                                    TRANSMETTRE LA REQUÊTE
                                    <svg class="w-6 h-6 transition-transform group-hover:translate-x-3 italic italic italic" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    html { scroll-behavior: smooth; }
</style>
@endsection