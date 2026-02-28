<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Gestion des cours
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Durée, frais d'inscription et tarifs par niveau / langue (Super Admin)
        </p>
    </x-slot>

    <style>
        .kpi-card {
            background:white; border:1px solid rgba(15,14,61,0.07); border-radius:18px;
            padding:20px 22px; box-shadow:0 2px 12px rgba(15,14,61,0.05);
            transition:all 0.2s cubic-bezier(0.25,0.46,0.45,0.94);
            position:relative; overflow:hidden;
        }
        .kpi-card:hover { transform:translateY(-3px); box-shadow:0 8px 28px rgba(15,14,61,0.1); }
        .kpi-card::after {
            content:''; position:absolute; top:0; left:0; right:0;
            height:3px; border-radius:18px 18px 0 0;
        }
        .kpi-blue::after  { background:linear-gradient(90deg,#1D1B84,#4f46e5); }
        .kpi-green::after { background:linear-gradient(90deg,#059669,#34d399); }
        .kpi-violet::after{ background:linear-gradient(90deg,#7c3aed,#a78bfa); }
        .kpi-slate::after { background:linear-gradient(90deg,#475569,#94a3b8); }

        .section-card {
            background:white; border:1px solid rgba(15,14,61,0.07);
            border-radius:20px; box-shadow:0 2px 16px rgba(15,14,61,0.05); overflow:hidden;
        }
        .section-header {
            padding:18px 22px 16px; border-bottom:1px solid #f1f5f9;
            display:flex; align-items:center; justify-content:space-between;
        }
        .section-title { display:flex; align-items:center; gap:10px; }
        .section-icon {
            width:34px; height:34px; border-radius:10px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }

        .erp-table { width:100%; border-collapse:collapse; }
        .erp-table thead tr { border-bottom:2px solid #f1f5f9; }
        .erp-table thead th {
            padding:10px 16px; font-size:10px; font-weight:800;
            color:#94a3b8; text-transform:uppercase; letter-spacing:0.14em;
            text-align:left; white-space:nowrap;
        }
        .erp-table tbody tr { border-bottom:1px solid #f8fafc; transition:background 0.15s; }
        .erp-table tbody tr:hover { background:#fafbff; }
        .erp-table tbody tr:last-child { border-bottom:none; }
        .erp-table tbody td { padding:13px 16px; vertical-align:middle; }

        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            padding:10px 18px;
            background:linear-gradient(135deg,#1D1B84 0%,#2d2bb0 100%);
            color:white; font-size:12.5px; font-weight:800; font-family:inherit;
            border:none; border-radius:11px; cursor:pointer;
            transition:all 0.2s; box-shadow:0 3px 14px rgba(29,27,132,0.3);
            text-decoration:none; white-space:nowrap;
        }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(29,27,132,0.38); }

        .level-badge {
            display:inline-flex; align-items:center; justify-content:center;
            width:36px; height:36px; border-radius:10px;
            font-size:12px; font-weight:900; flex-shrink:0;
        }
        .lang-badge {
            font-size:11px; font-weight:800; padding:3px 10px;
            border-radius:99px; white-space:nowrap;
        }
        .type-standard    { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
        .type-vorbereitung{ background:#faf5ff; color:#7c3aed; border:1px solid #ddd6fe; }

        .price-total {
            font-size:14px; font-weight:900; color:#0f172a; letter-spacing:-0.02em;
        }
        .price-inscription {
            font-size:12px; font-weight:700; color:#64748b;
        }
        .centre-pill {
            font-size:11px; font-weight:700; color:#475569;
            background:#f8fafc; border:1px solid #e2e8f0;
            padding:3px 9px; border-radius:7px; white-space:nowrap;
        }
        .empty-state {
            padding:48px 24px; text-align:center;
            color:#94a3b8; font-size:13px; font-weight:600;
        }

        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .a1{animation:fadeUp 0.4s 0.05s cubic-bezier(0.22,1,0.36,1) both}
        .a2{animation:fadeUp 0.4s 0.12s cubic-bezier(0.22,1,0.36,1) both}
        .a3{animation:fadeUp 0.4s 0.20s cubic-bezier(0.22,1,0.36,1) both}
    </style>

    <div style="display:flex; flex-direction:column; gap:22px; margin-top:4px;">

        {{-- ── KPI ROW ── --}}
        <div class="a1" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px;">

            <div class="kpi-card kpi-blue">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(29,27,132,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#1D1B84; background:rgba(29,27,132,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Total</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">{{ $fees->count() }}</p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Cours configurés</p>
            </div>

            <div class="kpi-card kpi-green">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(5,150,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#059669" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#059669; background:rgba(5,150,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Langues</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">{{ $fees->pluck('language')->unique()->count() }}</p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Langues différentes</p>
            </div>

            <div class="kpi-card kpi-violet">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(124,58,237,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#7c3aed" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#7c3aed; background:rgba(124,58,237,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Niveaux</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">{{ $fees->pluck('level.name')->unique()->filter()->count() }}</p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Niveaux distincts</p>
            </div>

            <div class="kpi-card kpi-slate">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(71,85,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#475569" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#475569; background:rgba(71,85,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Tarif moy.</span>
                </div>
                <p style="font-size:20px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $fees->count() ? number_format($fees->avg('total_amount'), 0, ',', ' ') : '—' }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">FCFA tarif moyen</p>
            </div>
        </div>

        {{-- ── TABLEAU COURS ── --}}
        <div class="section-card a2">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon" style="background:rgba(29,27,132,0.08);">
                        <svg width="17" height="17" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:14px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">Catalogue des cours</p>
                        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:1px 0 0;">
                            Exemple : A1 · Allemand · 120 000 FCFA
                        </p>
                    </div>
                </div>
                <a href="{{ route('tuition_fees.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau cours
                </a>
            </div>

            <div style="overflow-x:auto;">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Niveau</th>
                            <th>Langue</th>
                            <th>Type</th>
                            <th>Durée</th>
                            <th>Libellé</th>
                            <th>Centre</th>
                            <th>Inscription</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fees as $fee)
                            @php
                                $levelName = optional($fee->level)->name ?? '—';
                                $levelColors = [
                                    'A1'=>['bg'=>'rgba(5,150,105,0.09)','color'=>'#065f46'],
                                    'A2'=>['bg'=>'rgba(16,185,129,0.1)','color'=>'#047857'],
                                    'B1'=>['bg'=>'rgba(29,27,132,0.09)','color'=>'#1D1B84'],
                                    'B2'=>['bg'=>'rgba(79,70,229,0.1)','color'=>'#3730a3'],
                                    'C1'=>['bg'=>'rgba(124,58,237,0.1)','color'=>'#6d28d9'],
                                    'C2'=>['bg'=>'rgba(227,30,36,0.09)','color'=>'#9f1239'],
                                ];
                                $lc = $levelColors[$levelName] ?? ['bg'=>'rgba(71,85,105,0.09)','color'=>'#334155'];
                            @endphp
                            <tr>
                                {{-- Niveau --}}
                                <td>
                                    <div class="level-badge" style="background:{{ $lc['bg'] }}; color:{{ $lc['color'] }};">
                                        {{ $levelName }}
                                    </div>
                                </td>

                                {{-- Langue --}}
                                <td>
                                    @php
                                        $langColors = [
                                            'Allemand' => ['bg'=>'rgba(29,27,132,0.08)','color'=>'#1D1B84'],
                                            'Français' => ['bg'=>'rgba(227,30,36,0.08)','color'=>'#9f1239'],
                                            'Anglais'  => ['bg'=>'rgba(5,150,105,0.08)','color'=>'#065f46'],
                                            'Espagnol' => ['bg'=>'rgba(245,158,11,0.1)','color'=>'#92400e'],
                                        ];
                                        $lngC = $langColors[$fee->language] ?? ['bg'=>'rgba(71,85,105,0.08)','color'=>'#334155'];
                                    @endphp
                                    <span class="lang-badge" style="background:{{ $lngC['bg'] }}; color:{{ $lngC['color'] }}; border:1px solid {{ $lngC['bg'] }};">
                                        {{ $fee->language }}
                                    </span>
                                </td>

                                {{-- Type --}}
                                <td>
                                    @if($fee->course_type === 'vorbereitung')
                                        <span class="type-vorbereitung" style="display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                            Vorbereitung
                                        </span>
                                    @else
                                        <span class="type-standard" style="display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            Standard
                                        </span>
                                    @endif
                                </td>

                                {{-- Durée --}}
                                <td>
                                    <span style="display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:700; color:#334155;">
                                        <svg width="13" height="13" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                                        </svg>
                                        {{ $fee->duration_label ?: ($fee->duration_weeks ? $fee->duration_weeks.' sem.' : '—') }}
                                    </span>
                                </td>

                                {{-- Libellé --}}
                                <td>
                                    <span style="font-size:12.5px; color:#475569; font-weight:600;">
                                        {{ $fee->label ?: '—' }}
                                    </span>
                                </td>

                                {{-- Centre --}}
                                <td>
                                    <span class="centre-pill">
                                        {{ $fee->centre ? $fee->centre->name : 'Tous centres' }}
                                    </span>
                                </td>

                                {{-- Inscription --}}
                                <td>
                                    <span class="price-inscription">
                                        {{ number_format($fee->inscription_fee ?? 10000, 0, ',', ' ') }}
                                        <span style="font-size:10px; color:#94a3b8; margin-left:1px;">{{ $fee->currency }}</span>
                                    </span>
                                </td>

                                {{-- Total --}}
                                <td>
                                    <div style="display:flex; flex-direction:column; gap:1px;">
                                        <span class="price-total">
                                            {{ number_format($fee->total_amount, 0, ',', ' ') }}
                                            <span style="font-size:10px; font-weight:700; color:#94a3b8; margin-left:2px;">{{ $fee->currency }}</span>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div style="width:48px; height:48px; border-radius:14px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                                            <svg width="22" height="22" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        Aucun tarif défini pour le moment.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
