<x-app-layout>
    {{-- ════ HEADER NETTOYÉ ════ --}}
    <x-slot name="header">
        <div class="flex items-center justify-between py-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-lg">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-900 tracking-tight leading-none uppercase tracking-widest">
                        Console Secrétariat
                    </h1>
                    <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">
                        {{ auth()->user()->centre->name ?? 'Centre Tara' }} · {{ now()->translatedFormat('l d F') }}
                    </p>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Système en ligne</span>
            </div>
        </div>
    </x-slot>

    <div class="pb-14 space-y-6 mt-4">

        {{-- ════ ALERTES ET ACTIONS PRIORITAIRES ════ --}}
        <div class="flex flex-wrap gap-3">
            @if($pendingTeacherValidations > 0)
            <a href="{{ route('teacher.validation') }}" class="db-alert-tag bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100 transition">
                <span class="db-dot bg-amber-500 animate-pulse"></span>
                {{ $pendingTeacherValidations }} présence(s) prof. à valider
            </a>
            @endif

            @if($expiredCycles > 0)
            <a href="{{ route('students.index') }}" class="db-alert-tag bg-rose-50 border-rose-200 text-rose-700 hover:bg-rose-100 transition">
                <span class="db-dot bg-rose-500 animate-bounce"></span>
                {{ $expiredCycles }} fin(s) de cycle détectée(s)
            </a>
            @endif

            @if($pendingSupport > 0)
            <a href="{{ route('support_requests.index') }}" class="db-alert-tag bg-indigo-50 border-indigo-200 text-indigo-700 hover:bg-indigo-100 transition">
                <span class="db-dot bg-indigo-500"></span>
                {{ $pendingSupport }} réclamation support
            </a>
            @endif
        </div>

        {{-- ════ ZONE 1 · BENTO KPI ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            {{-- Carte Financière Sombre (Encaissements) --}}
            <div class="lg:col-span-4 db-card-dark flex flex-col justify-between overflow-hidden relative min-h-[240px]">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <span class="db-chip-light">Recettes du mois</span>
                        <div class="db-icon-dark bg-emerald-500/20 text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="db-label" style="color:rgba(255,255,255,0.4)">Total encaissé (ventilation auto)</p>
                    <p class="text-[2.5rem] font-black text-white tracking-tight leading-none">
                        {{ number_format($totalRevenue, 0, ',', ' ') }}
                        <span class="text-lg font-bold text-emerald-500/60 ml-1">F</span>
                    </p>
                </div>
                <div class="mt-8 pt-5 border-t border-white/[0.07] flex justify-between items-end">
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Bilan courant</p>
                        <p class="text-xl font-black {{ $benefit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">{{ number_format($benefit, 0, ',', ' ') }} F</p>
                    </div>
                    <a href="{{ route('cash.index') }}" class="text-[9px] font-black text-blue-400 uppercase tracking-widest hover:text-blue-300">Voir Caisse →</a>
                </div>
            </div>

            {{-- Grille KPI --}}
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 grid-rows-2 gap-4">
                {{-- Étudiants --}}
                <div class="db-card">
                    <div class="db-icon bg-blue-50 text-blue-600 mb-4"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <p class="db-label">Effectif</p>
                    <p class="db-value">{{ $totalStudents }}</p>
                    <div class="mt-3 db-track"><div class="db-fill bg-blue-500" style="--w: {{ $totalStudents > 0 ? ($activeStudents/$totalStudents)*100 : 0 }}%"></div></div>
                </div>

                {{-- Dépenses --}}
                <div class="db-card">
                    <div class="db-icon bg-rose-50 text-rose-500 mb-4"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <p class="db-label">Dépenses</p>
                    <p class="db-value">{{ number_format($totalExpenses, 0, ',', ' ') }}</p>
                    <p class="text-[9px] font-bold text-slate-400 mt-2">Sorties de caisse</p>
                </div>

                {{-- Insolvables --}}
                <div class="db-card {{ $insolventCount > 0 ? 'bg-rose-50/20 border-rose-100' : '' }}">
                    <div class="db-icon bg-rose-50 text-rose-600 mb-4"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <p class="db-label">Dettes</p>
                    <p class="db-value" style="color:#e11d48">{{ $insolventCount }}</p>
                    <p class="text-[9px] font-bold text-rose-400 mt-2 uppercase">À relancer</p>
                </div>

                {{-- Raccourcis Rapides --}}
                <a href="{{ route('students.create') }}" class="db-card group hover:bg-slate-900 transition-colors">
                    <div class="db-icon bg-slate-100 text-slate-900 group-hover:bg-white/10 group-hover:text-white mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <p class="text-[10px] font-black group-hover:text-white uppercase tracking-widest">Inscrire</p>
                </a>

                <a href="{{ route('cash.open_today') }}" class="db-card group hover:bg-blue-600 transition-colors">
                    <div class="db-icon bg-blue-50 text-blue-600 group-hover:bg-white/10 group-hover:text-white mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <p class="text-[10px] font-black group-hover:text-white uppercase tracking-widest">Caisse</p>
                </a>

                <a href="{{ route('student_attendances.index') }}" class="db-card group hover:bg-indigo-600 transition-colors">
                    <div class="db-icon bg-indigo-50 text-indigo-600 group-hover:bg-white/10 group-hover:text-white mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <p class="text-[10px] font-black group-hover:text-white uppercase tracking-widest">Appels</p>
                </a>
            </div>
        </div>

        {{-- ════ ZONE 2 · DERNIERS PAIEMENTS ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
            <div class="lg:col-span-3 db-card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="db-card-title text-slate-900">Derniers Encaissements (Cycle)</h3>
                    <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest">Temps réel</span>
                </div>
                <div class="space-y-4">
                    @foreach($recentPayments as $p)
                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-[10px]">
                                {{ strtoupper(substr($p->student->first_name,0,1)) }}{{ strtoupper(substr($p->student->last_name,0,1)) }}
                            </div>
                            <div>
                                <p class="text-[12px] font-black text-slate-800 leading-tight">{{ $p->student->first_name }} {{ $p->student->last_name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ $p->type }} · {{ $p->mode }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[12px] font-black text-emerald-600">+ {{ number_format($p->amount, 0, ',', ' ') }} F</span>
                            <p class="text-[8px] font-bold text-slate-300 uppercase">{{ $p->payment_date }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- MINI CALENDRIER OU INFO --}}
            <div class="lg:col-span-2 db-card bg-slate-50 border-dashed border-slate-200 flex flex-col justify-center items-center text-center p-8">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-blue-600 mb-4">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-sm font-black text-slate-800">Prêt pour la journée ?</h4>
                <p class="text-xs text-slate-400 mt-2 leading-relaxed px-4">
                    Vérifiez les présences professeurs et assurez-vous que tous les versements sont bien ventilés.
                </p>
            </div>
        </div>
    </div>

    {{-- ════ STYLES CSS (Synchronisés avec les autres dashboards) ════ --}}
    <style>
    .db-card { background:#fff; border:1px solid #f1f5f9; border-radius:1.75rem; padding:1.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: all 0.3s ease; }
    .db-card-dark { background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); border-radius:1.75rem; padding:2rem; box-shadow:0 15px 40px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.05); }
    .db-label { font-size:.62rem; font-weight:900; text-transform:uppercase; letter-spacing:.18em; color:#94a3b8; margin-bottom:.3rem; }
    .db-value { font-size:2rem; font-weight:900; color:#0f172a; line-height:1; letter-spacing:-.03em; }
    .db-card-title { font-size:.75rem; font-weight:900; text-transform:uppercase; letter-spacing:.15em; }
    .db-track { height:7px; background:#f1f5f9; border-radius:999px; overflow:hidden; }
    .db-fill { height:100%; border-radius:999px; width:0; animation:dbGrow 1.2s cubic-bezier(.16,1,.3,1) forwards; }
    @keyframes dbGrow { from { width:0 } to { width:var(--w) } }
    .db-alert-tag { display:inline-flex; align-items:center; gap:.6rem; padding:.5rem 1.2rem; border-radius:999px; border:1px solid; font-size:.68rem; font-weight:800; text-decoration:none; }
    .db-dot { width:7px; height:7px; border-radius:50%; display:inline-block; }
    .db-chip-light { font-size: .55rem; font-weight: 900; letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,0.6); background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); padding: .3rem .8rem; border-radius: 999px; }
    .db-icon { width:42px; height:42px; border-radius:14px; display:flex; align-items:center; justify-content:center; }
    .db-icon-dark { width:38px; height:38px; border-radius:12px; display:flex; align-items:center; justify-content:center; }
    </style>
</x-app-layout>