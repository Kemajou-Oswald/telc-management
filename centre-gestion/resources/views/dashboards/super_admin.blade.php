<x-app-layout>

<x-slot name="header">
    <div class="py-3">
        <div class="flex items-center gap-2 mb-0.5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-[9px] font-black uppercase tracking-[.25em] text-slate-400">Live · Tableau de bord</span>
        </div>
        <h1 class="text-xl font-black text-slate-900 tracking-tight leading-none">Vue Panoramique</h1>
        <p class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-widest">Analyse multi-sites & performances</p>
    </div>
</x-slot>

<div class="pb-14 space-y-5">

    {{-- ═══ ZONE FILTRES ═══════════ --}}
    <form method="GET" action="{{ route('dashboard') }}" class="db-card flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2 flex-1">
            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 7.586V4z"/>
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[.2em] text-slate-400">Filtres</span>
        </div>

        {{-- Période --}}
        <div class="flex bg-slate-100 p-[3px] rounded-xl border border-slate-200/70 gap-[2px]">
            @foreach([['month','Ce mois'],['quarter','Trimestre'],['year','Année'],['all','Tout']] as $p)
            <button type="submit" name="period" value="{{ $p[0] }}"
                class="px-4 py-2 rounded-[9px] text-[10px] font-black transition-all {{ request('period','month') === $p[0] ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}">
                {{ $p[1] }}
            </button>
            @endforeach
        </div>

        {{-- Centre --}}
        <select name="centre" onchange="this.form.submit()"
            class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-600 outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer">
            <option value="">Tous les centres</option>
            @foreach($centres as $c)
            <option value="{{ $c->id }}" {{ request('centre') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>

        {{-- Période active affichée (Correction du ?-> pour compatibilité PHP 7) --}}
        <div class="ml-auto flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                @if(request('period','month') === 'month') Ce mois
                @elseif(request('period') === 'quarter') Trimestre
                @elseif(request('period') === 'year') Année 2026
                @else Toute la période
                @endif
                
                @if(request('centre'))
                    @php $selCentre = $centres->firstWhere('id', request('centre')); @endphp
                    @if($selCentre) · {{ $selCentre->name }} @endif
                @endif
            </span>
        </div>
    </form>

    {{-- ═══ ALERTES ════════════════════════════════════════════════════ --}}
    @if(($pendingSupport ?? 0) > 0 || ($lowStock ?? 0) > 0 || ($insolventCount ?? 0) > 0)
    <div class="flex flex-wrap gap-2.5">
        @if(($pendingSupport ?? 0) > 0)
        <a href="{{ route('support_requests.index') }}" class="db-alert-tag bg-rose-50 border-rose-200/80 text-rose-600 hover:bg-rose-100">
            <span class="db-dot bg-rose-500 animate-pulse"></span>
            {{ $pendingSupport }} demande support en attente
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        @endif
        @if(($lowStock ?? 0) > 0)
        <a href="{{ route('stock.index') }}" class="db-alert-tag bg-amber-50 border-amber-200/80 text-amber-600 hover:bg-amber-100">
            <span class="db-dot bg-amber-500 animate-pulse"></span>
            {{ $lowStock }} produit en rupture
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        @endif
        @if(($insolventCount ?? 0) > 0)
        <div class="db-alert-tag bg-purple-50 border-purple-200/80 text-purple-600">
            <span class="db-dot bg-purple-500"></span>
            {{ $insolventCount }} étudiant(s) insolvable(s)
        </div>
        @endif
    </div>
    @endif

    {{-- ═══ BENTO GRID ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <div class="lg:col-span-4 db-card-dark flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-8">
                    <span class="db-chip-light">Résultat financier</span>
                    <div class="db-icon-dark bg-emerald-500/20 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[.22em] text-slate-500 mb-1">Chiffre d'affaires</p>
                <p class="text-[2.4rem] font-black text-white tracking-tight leading-none">
                    {{ number_format($totalRevenue ?? 0, 0, ',', ' ') }}
                    <span class="text-xl font-bold text-slate-500 ml-1">F</span>
                </p>
                <div class="my-6 border-t border-white/[0.07]"></div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-[.2em] text-slate-500">Dépenses</span>
                    <span class="text-sm font-black text-rose-400">- {{ number_format($totalExpenses ?? 0, 0, ',', ' ') }} F</span>
                </div>
                <div class="mt-3 h-1.5 bg-white/[0.06] rounded-full overflow-hidden">
                    @php $expRatio = ($totalRevenue ?? 0) > 0 ? (($totalExpenses ?? 0) / $totalRevenue) * 100 : 0; @endphp
                    <div class="h-full rounded-full bg-rose-500/60 db-bar" style="--w: {{ min($expRatio, 100) }}%"></div>
                </div>
            </div>
            <div class="mt-6 pt-5 border-t border-white/[0.07]">
                <p class="text-[9px] font-black uppercase tracking-[.25em] text-slate-500 mb-2">Bénéfice net</p>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-black {{ ($benefit ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }} tracking-tight">
                        {{ ($benefit ?? 0) >= 0 ? '+' : '' }}{{ number_format($benefit ?? 0, 0, ',', ' ') }} F
                    </p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 grid-rows-2 gap-4">
            {{-- Effectif --}}
            <div class="db-card">
                <div class="flex items-start justify-between mb-4">
                    <div class="db-icon bg-blue-50 text-blue-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <span class="db-badge bg-blue-50 text-blue-600">Actifs · {{ $activeStudents ?? 0 }}</span>
                </div>
                <p class="db-label">Effectif total</p>
                <p class="db-value">{{ number_format($totalStudents ?? 0) }}</p>
                <div class="mt-3 db-track">
                    @php $actPct = ($totalStudents ?? 0) > 0 ? (($activeStudents ?? 0) / $totalStudents) * 100 : 0; @endphp
                    <div class="db-fill bg-blue-500" style="--w: {{ $actPct }}%"></div>
                </div>
            </div>

            {{-- Recouvrement --}}
            <div class="db-card">
                <div class="flex items-start justify-between mb-4">
                    <div class="db-icon bg-emerald-50 text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                </div>
                <p class="db-label">Recouvrement</p>
                <p class="db-value">{{ $solvabilityRate ?? 0 }}%</p>
                <div class="mt-3 db-track">
                    <div class="db-fill bg-emerald-500" style="--w: {{ $solvabilityRate ?? 0 }}%"></div>
                </div>
            </div>

            {{-- Fins de cycle --}}
            <div class="db-card {{ ($expiredCycles ?? 0) > 0 ? 'bg-amber-50/30 border-amber-100' : '' }}">
                <div class="db-icon bg-amber-50 text-amber-600 mb-4"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <p class="db-label">Fins de cycle</p>
                <p class="db-value {{ ($expiredCycles ?? 0) > 0 ? 'text-amber-600' : '' }}">{{ $expiredCycles ?? 0 }}</p>
            </div>

            {{-- Centres --}}
            <div class="db-card">
                <p class="db-label">Centres actifs</p>
                <p class="db-value">{{ $centres->count() }}</p>
                <div class="mt-3 flex flex-wrap gap-1">
                    @foreach($centres->take(3) as $c)
                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-600 uppercase">{{ $c->name }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Filières --}}
            <div class="db-card">
                <p class="db-label">Filières actives</p>
                <p class="db-value">{{ ($languageStats ?? collect())->count() }}</p>
                <div class="mt-3 flex flex-wrap gap-1">
                    @foreach(($languageStats ?? collect())->take(3) as $ls)
                    <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-violet-50 text-violet-600 uppercase">{{ $ls->language }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Incidents --}}
            <div class="db-card">
                <p class="db-label">Incidents</p>
                <p class="db-value">{{ ($lowStock ?? 0) + ($pendingSupport ?? 0) }}</p>
            </div>
        </div>
    </div>

    {{-- ZONE ANALYSE --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-3 db-card">
            <h3 class="db-card-title mb-6">Effectifs par centre</h3>
            @php $maxSt = $centres->max('students_count') ?: 1; @endphp
            <div class="space-y-5">
                @foreach($centres as $c)
                @php $p = ($c->students_count / $maxSt) * 100; @endphp
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-xs font-black text-slate-700">{{ $c->name }}</span>
                        <span class="text-[13px] font-black text-slate-900">{{ $c->students_count }}</span>
                    </div>
                    <div class="db-track"><div class="db-fill bg-indigo-500" style="--w: {{ $p }}%"></div></div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2 db-card">
            <h3 class="db-card-title mb-6">Revenus par filière</h3>
            @php $maxRev = ($languageStats ?? collect())->max('total') ?: 1; @endphp
            <div class="space-y-4">
                @foreach(($languageStats ?? collect()) as $stat)
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-[9px] font-black px-2 py-0.5 rounded bg-blue-50 text-blue-600 uppercase">{{ $stat->language }}</span>
                        <span class="text-[12px] font-black">{{ number_format($stat->total, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="db-track"><div class="db-fill bg-emerald-400" style="--w: {{ ($stat->total / $maxRev) * 100 }}%"></div></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RACCOURCIS + RÉCENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        <div class="lg:col-span-4 db-card grid grid-cols-2 gap-3">
            <a href="{{ route('students.index') }}" class="db-shortcut group hover:bg-blue-600 transition">
                <div class="db-sh-icon bg-blue-50 text-blue-600 group-hover:bg-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                <span class="text-[9px] font-black group-hover:text-white mt-2">ÉTUDIANTS</span>
            </a>
            <a href="{{ route('stock.index') }}" class="db-shortcut group hover:bg-emerald-600 transition">
                <div class="db-sh-icon bg-emerald-50 text-emerald-600 group-hover:bg-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                <span class="text-[9px] font-black group-hover:text-white mt-2">STOCKS</span>
            </a>
        </div>

        <div class="lg:col-span-8 db-card">
            <h3 class="db-card-title mb-5">Dernières recettes</h3>
            <table class="w-full">
                <tbody class="divide-y divide-slate-50">
                    @foreach(($recentPayments ?? []) as $pay)
                    <tr>
                        <td class="py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-[9px] font-black">{{ substr($pay->student->first_name ?? '?',0,1) }}</div>
                            <span class="text-xs font-black">{{ $pay->student->first_name ?? '' }} {{ $pay->student->last_name ?? '' }}</span>
                        </td>
                        <td class="py-3 text-[10px] font-bold text-slate-400 uppercase text-right">+ {{ number_format($pay->amount, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
.db-card { background:#fff; border:1px solid #f1f5f9; border-radius:1.5rem; padding:1.5rem; }
.db-card-dark { background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); border-radius:1.5rem; padding:1.75rem; box-shadow:0 8px 32px rgba(0,0,0,.2); }
.db-label { font-size:.6rem; font-weight:900; text-transform:uppercase; letter-spacing:.22em; color:#94a3b8; margin-bottom:.2rem; }
.db-value { font-size:1.9rem; font-weight:900; color:#0f172a; line-height:1; letter-spacing:-.03em; }
.db-card-title { font-size:.7rem; font-weight:900; text-transform:uppercase; letter-spacing:.18em; }
.db-icon { width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; }
.db-icon-dark { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
.db-track { height:6px; background:#f1f5f9; border-radius:999px; overflow:hidden; }
.db-fill { height:100%; border-radius:999px; width:0; animation:dbGrow 1s forwards; }
@keyframes dbGrow { from{width:0} to{width:var(--w)} }
.db-bar { width:0; animation:dbGrow 1.1s both; }
.db-alert-tag { display:inline-flex; align-items:center; gap:.5rem; padding:.4rem 1rem; border-radius:999px; border:1px solid; font-size:.65rem; font-weight:800; text-decoration:none; transition:.15s; }
.db-dot { width:6px; height:6px; border-radius:50%; display:inline-block; }
.db-shortcut { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:1rem; border-radius:1rem; border:1px solid #f1f5f9; background:#fafafa; text-decoration:none; }
.db-sh-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
.st-tab { padding:.5rem 1.2rem; border-radius:.9rem; font-size:.65rem; font-weight:900; text-transform:uppercase; color:#94a3b8; text-decoration:none; transition:.2s; }
.st-tab.is-active { background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; }
</style>

</x-app-layout>