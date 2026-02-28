<x-app-layout>
    {{-- ════ EN-TÊTE NETTOYÉ (HEADER) ════ --}}
    <x-slot name="header">
        <div class="flex items-center gap-3 py-2">
            <div class="relative flex items-center justify-center">
                <span class="w-3 h-3 rounded-full bg-blue-500 animate-ping absolute opacity-20"></span>
                <span class="w-2 h-2 rounded-full bg-blue-600 relative"></span>
            </div>
            <div>
                <h1 class="text-lg font-black text-slate-900 tracking-tight leading-none uppercase tracking-widest">
                    {{ auth()->user()->centre->name ?? 'Mon Centre' }}
                </h1>
                <p class="text-[10px] text-slate-400 font-bold mt-1">Console de Direction · Vue Globale</p>
            </div>
        </div>
    </x-slot>

    <div class="pb-14 space-y-6 mt-4">

        {{-- ════ ZONE FILTRES DISCRÈTE (DÉPLACÉE ICI) ════ --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <form method="GET" action="{{ route('dashboards.directeur') }}" class="flex items-center gap-2 bg-white/50 p-1.5 rounded-2xl border border-slate-100 shadow-sm" id="dashForm">
                <div class="flex gap-1">
                    @foreach([['month','Mois'],['quarter','Trimestre'],['year','Année'],['all','Global']] as $p)
                    <button type="submit" name="period" value="{{ $p[0] }}"
                        class="px-4 py-2 rounded-xl text-[10px] font-black transition-all {{ request('period','month') === $p[0] ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:bg-slate-50' }}">
                        {{ $p[1] }}
                    </button>
                    @endforeach
                </div>
                <div class="w-px h-6 bg-slate-200 mx-1"></div>
                <select name="level" onchange="this.form.submit()"
                    class="border-none bg-transparent text-[11px] font-black text-slate-600 focus:ring-0 cursor-pointer">
                    <option value="">Tous les niveaux</option>
                    @foreach($levels as $lv)
                    <option value="{{ $lv->id }}" {{ request('level') == $lv->id ? 'selected' : '' }}>{{ $lv->name }}</option>
                    @endforeach
                </select>
            </form>

            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ now()->translatedFormat('l d F Y') }}</span>
            </div>
        </div>

        {{-- ════ ALERTES OPÉRATIONNELLES ════ --}}
        @if(($expiredCycles ?? 0) > 0 || ($insolventCount ?? 0) > 0 || ($pendingSupport ?? 0) > 0)
        <div class="flex flex-wrap gap-2.5">
            @if($expiredCycles > 0)
            <div class="db-alert-tag bg-amber-50 border-amber-200 text-amber-700">
                <span class="db-dot bg-amber-500 animate-pulse"></span>
                {{ $expiredCycles }} élève(s) en fin de cycle
            </div>
            @endif
            @if($insolventCount > 0)
            <div class="db-alert-tag bg-rose-50 border-rose-200 text-rose-700">
                <span class="db-dot bg-rose-500"></span>
                {{ $insolventCount }} débiteur(s) critique(s)
            </div>
            @endif
            @if(($pendingSupport ?? 0) > 0)
            <a href="{{ route('support_requests.index') }}" class="db-alert-tag bg-indigo-50 border-indigo-200 text-indigo-700">
                <span class="db-dot bg-indigo-500 animate-bounce"></span>
                {{ $pendingSupport }} réclamation(s) support
            </a>
            @endif
        </div>
        @endif

        {{-- ════ ZONE 1 · BENTO KPI ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            {{-- Bloc Financier Sombre (Mise en avant) --}}
            <div class="lg:col-span-4 db-card-dark flex flex-col justify-between overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <span class="db-chip-light">Trésorerie Actuelle</span>
                        <div class="db-icon-dark bg-blue-500/20 text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="db-label" style="color:rgba(255,255,255,0.4)">Total encaissé sur la période</p>
                    <p class="text-[2.6rem] font-black text-white tracking-tight leading-none">
                        {{ number_format($totalRevenue, 0, ',', ' ') }}
                        <span class="text-lg font-bold text-slate-500 ml-1">F</span>
                    </p>
                    <div class="my-6 border-t border-white/[0.07]"></div>
                    <div class="flex justify-between items-center text-[10px] font-black uppercase text-slate-500 tracking-widest">
                        <span>Santé Financière</span>
                        <span class="{{ $solvabilityRate > 75 ? 'text-emerald-400' : 'text-amber-400' }}">{{ $solvabilityRate }}% Recouvrement</span>
                    </div>
                    <div class="mt-2 db-track bg-white/10"><div class="db-fill bg-blue-500" style="--w: {{ $solvabilityRate }}%"></div></div>
                </div>
                <div class="mt-8 pt-5 border-t border-white/[0.07]">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Bilan Net</p>
                            <p class="text-2xl font-black {{ $benefit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">{{ number_format($benefit, 0, ',', ' ') }} F</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Dépenses</p>
                            <p class="text-sm font-black text-rose-400/80">- {{ number_format($totalExpenses, 0, ',', ' ') }} F</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grille KPI Droite --}}
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 grid-rows-2 gap-4">
                {{-- Effectif --}}
                <div class="db-card group">
                    <div class="db-icon bg-blue-50 text-blue-600 mb-4 group-hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <p class="db-label">Effectif</p>
                    <p class="db-value">{{ $totalStudents }}</p>
                    <div class="mt-4 db-track"><div class="db-fill bg-blue-500" style="--w: {{ $totalStudents > 0 ? ($activeStudents/$totalStudents)*100 : 0 }}%"></div></div>
                    <p class="text-[9px] font-bold text-slate-400 mt-2">{{ $activeStudents }} élèves en cours</p>
                </div>

                {{-- Fins de cycle --}}
                <div class="db-card {{ $expiredCycles > 0 ? 'bg-amber-50/50 border-amber-200' : '' }}">
                    <div class="db-icon bg-amber-50 text-amber-600 mb-4 animate-bounce"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <p class="db-label">Alerte Temps</p>
                    <p class="db-value {{ $expiredCycles > 0 ? 'text-amber-600' : '' }}">{{ $expiredCycles }}</p>
                    <p class="text-[9px] font-black text-amber-700/60 mt-2 uppercase tracking-tight">Fin de niveau atteinte</p>
                </div>

                {{-- Classes --}}
                <div class="db-card">
                    <div class="db-icon bg-indigo-50 text-indigo-600 mb-4"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 01-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                    <p class="db-label">Classes</p>
                    <p class="db-value">{{ $totalGroups }}</p>
                    <p class="text-[9px] font-bold text-slate-400 mt-2 italic">Groupes pédagogiques</p>
                </div>

                {{-- Langues --}}
                <div class="db-card">
                    <p class="db-label">Filières</p>
                    <p class="db-value" style="font-size:1.6rem">{{ $languageStats->count() }}</p>
                    <div class="mt-4 flex flex-wrap gap-1">
                        @foreach($languageStats->take(2) as $stat) 
                            <span class="text-[7px] font-black px-2 py-0.5 rounded-lg bg-slate-100 uppercase text-slate-600">{{ $stat->language }}</span> 
                        @endforeach
                    </div>
                </div>

                {{-- Solvabilité --}}
                <div class="db-card">
                    <p class="db-label">Taux d'Insolvabilité</p>
                    @php $insPct = $totalStudents > 0 ? round(($insolventCount / $totalStudents) * 100) : 0; @endphp
                    <p class="db-value" style="font-size:1.6rem; color:{{ $insPct > 20 ? '#dc2626' : '#0f172a' }}">{{ $insPct }}%</p>
                    <p class="text-[9px] font-bold text-rose-400 mt-2 uppercase tracking-tighter">Élèves en retard</p>
                </div>

                {{-- Stock --}}
                <div class="db-card {{ ($lowStock ?? 0) > 0 ? 'bg-rose-50/20 border-rose-100' : '' }}">
                    <p class="db-label">Matériels</p>
                    <p class="db-value" style="font-size:1.6rem">{{ $lowStock ?? 0 }}</p>
                    <p class="text-[9px] font-bold text-rose-500 mt-2 uppercase">Stock critique</p>
                </div>
            </div>
        </div>

        {{-- ════ ZONE 2 · PERFORMANCE & RÉCENT ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
            {{-- Graphique Langues --}}
            <div class="lg:col-span-3 db-card">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="db-card-title text-slate-900">Distribution par Langue (Ventes)</h3>
                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Données Périodiques</span>
                </div>
                @php $maxL = $languageStats->max('total') ?: 1; @endphp
                <div class="space-y-6">
                    @foreach($languageStats as $ls)
                    @php $lp = ($ls->total / $maxL) * 100; @endphp
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[12px] font-black text-slate-700 uppercase tracking-tight">{{ $ls->language }} ({{ $ls->count }} élèves)</span>
                            <span class="text-[12px] font-black text-blue-600 tabular-nums">{{ number_format($ls->total, 0, ',', ' ') }} F</span>
                        </div>
                        <div class="db-track h-2"><div class="db-fill bg-gradient-to-r from-blue-600 to-blue-400" style="--w: {{ $lp }}%"></div></div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Flux Récent --}}
            <div class="lg:col-span-2 db-card flex flex-col justify-between">
                <div>
                    <h3 class="db-card-title mb-6 text-slate-900">Derniers Encaissements</h3>
                    <div class="space-y-4">
                        @foreach($recentPayments as $p)
                        <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-[10px] shadow-sm">
                                    {{ strtoupper(substr($p->student->first_name,0,1)) }}{{ strtoupper(substr($p->student->last_name,0,1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[12px] font-black text-slate-800 leading-tight truncate">{{ $p->student->first_name }} {{ $p->student->last_name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5 tracking-tighter">{{ $p->type }}</p>
                                </div>
                            </div>
                            <span class="text-[12px] font-black text-emerald-600 whitespace-nowrap">+ {{ number_format($p->amount, 0, ',', ' ') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('students.index') }}" class="flex items-center justify-center gap-2 py-3 bg-slate-50 rounded-2xl text-[9px] font-black text-blue-500 uppercase tracking-widest hover:bg-blue-50 transition border border-slate-100">
                        Accéder au registre complet
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2.5"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ════ STYLES CSS LOCAUX ════ --}}
    <style>
    .db-card { background:#fff; border:1px solid #f1f5f9; border-radius:1.75rem; padding:1.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: all 0.3s ease; }
    .db-card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
    .db-card-dark { background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); border-radius:1.75rem; padding:2rem; box-shadow:0 15px 40px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.05); }
    .db-label { font-size:.62rem; font-weight:900; text-transform:uppercase; letter-spacing:.18em; color:#94a3b8; margin-bottom:.3rem; }
    .db-value { font-size:2rem; font-weight:900; color:#0f172a; line-height:1; letter-spacing:-.03em; }
    .db-card-title { font-size:.75rem; font-weight:900; text-transform:uppercase; letter-spacing:.15em; }
    .db-track { height:7px; background:#f1f5f9; border-radius:999px; overflow:hidden; }
    .db-fill { height:100%; border-radius:999px; width:0; animation:dbGrow 1.2s cubic-bezier(.16,1,.3,1) forwards; }
    @keyframes dbGrow { from { width:0 } to { width:var(--w) } }
    .db-alert-tag { display:inline-flex; align-items:center; gap:.6rem; padding:.5rem 1.2rem; border-radius:999px; border:1px solid; font-size:.68rem; font-weight:800; text-decoration:none; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
    .db-dot { width:7px; height:7px; border-radius:50%; display:inline-block; }
    .db-chip-light { font-size: .55rem; font-weight: 900; letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,0.6); background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); padding: .3rem .8rem; border-radius: 999px; }
    .db-icon { width:42px; height:42px; border-radius:14px; display:flex; align-items:center; justify-content:center; }
    .db-icon-dark { width:38px; height:38px; border-radius:12px; display:flex; align-items:center; justify-content:center; }
    </style>
</x-app-layout>