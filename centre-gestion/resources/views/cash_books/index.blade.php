<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Caisse journalière
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Suivi des journées de caisse (dernier mois)
        </p>
    </x-slot>

    <style>
        .kpi-card {
            background: white;
            border: 1px solid rgba(15,14,61,0.07);
            border-radius: 18px;
            padding: 20px 22px;
            box-shadow: 0 2px 12px rgba(15,14,61,0.05);
            transition: all 0.2s cubic-bezier(0.25,0.46,0.45,0.94);
            position: relative;
            overflow: hidden;
        }
        .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(15,14,61,0.1); }
        .kpi-card::after {
            content:''; position:absolute; top:0; left:0; right:0;
            height:3px; border-radius:18px 18px 0 0;
        }
        .kpi-green::after  { background: linear-gradient(90deg,#059669,#34d399); }
        .kpi-red::after    { background: linear-gradient(90deg,#E31E24,#f97316); }
        .kpi-blue::after   { background: linear-gradient(90deg,#1D1B84,#4f46e5); }
        .kpi-slate::after  { background: linear-gradient(90deg,#475569,#94a3b8); }

        .section-card {
            background: white;
            border: 1px solid rgba(15,14,61,0.07);
            border-radius: 20px;
            box-shadow: 0 2px 16px rgba(15,14,61,0.05);
            overflow: hidden;
        }
        .section-header {
            padding: 18px 22px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
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
        .erp-table tbody tr {
            border-bottom:1px solid #f8fafc; transition:background 0.15s;
        }
        .erp-table tbody tr:hover { background:#fafbff; }
        .erp-table tbody tr:last-child { border-bottom:none; }
        .erp-table tbody td { padding:13px 16px; vertical-align:middle; }

        .btn-success {
            display:inline-flex; align-items:center; gap:8px;
            padding:11px 20px;
            background: linear-gradient(135deg,#059669 0%,#10b981 100%);
            color:white; font-size:13px; font-weight:800; font-family:inherit;
            border:none; border-radius:12px; cursor:pointer;
            transition:all 0.2s; box-shadow:0 3px 16px rgba(5,150,105,0.3);
        }
        .btn-success:hover { transform:translateY(-2px); box-shadow:0 6px 24px rgba(5,150,105,0.38); }

        .badge-open   { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:4px 11px; border-radius:99px; font-size:11px; font-weight:800; display:inline-flex; align-items:center; gap:5px; }
        .badge-closed { background:#f8fafc; color:#475569; border:1px solid #e2e8f0; padding:4px 11px; border-radius:99px; font-size:11px; font-weight:800; display:inline-flex; align-items:center; gap:5px; }

        .link-voir {
            display:inline-flex; align-items:center; gap:5px;
            font-size:11.5px; font-weight:800; color:#1D1B84;
            text-decoration:none; padding:5px 12px;
            border:1.5px solid rgba(29,27,132,0.15); border-radius:8px;
            transition:all 0.15s; background:rgba(29,27,132,0.03);
        }
        .link-voir:hover { background:rgba(29,27,132,0.08); border-color:rgba(29,27,132,0.3); }

        .info-banner {
            background: linear-gradient(135deg, rgba(29,27,132,0.04) 0%, rgba(227,30,36,0.03) 100%);
            border: 1px solid rgba(29,27,132,0.1);
            border-radius: 16px;
            padding: 18px 22px;
            display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
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

        {{-- KPI ROW --}}
        <div class="a1" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px;">

            <div class="kpi-card kpi-green">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(5,150,105,0.09); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#059669" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#059669; background:rgba(5,150,105,0.09); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Entrées</span>
                </div>
                <p style="font-size:20px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ number_format($cashBooks->sum('total_entrees'), 0, ',', ' ') }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0; text-transform:uppercase; letter-spacing:0.04em;">FCFA total entrées</p>
            </div>

            <div class="kpi-card kpi-red">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(227,30,36,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#E31E24" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#E31E24; background:rgba(227,30,36,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Sorties</span>
                </div>
                <p style="font-size:20px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ number_format($cashBooks->sum('total_sorties'), 0, ',', ' ') }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0; text-transform:uppercase; letter-spacing:0.04em;">FCFA total sorties</p>
            </div>

            <div class="kpi-card kpi-blue">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(29,27,132,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#1D1B84; background:rgba(29,27,132,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Solde</span>
                </div>
                <p style="font-size:20px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{-- Modification ici : utilisation de optional() pour éviter l'erreur syntaxe --}}
                    {{ number_format(optional($cashBooks->last())->solde_final ?? 0, 0, ',', ' ') }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0; text-transform:uppercase; letter-spacing:0.04em;">FCFA solde actuel</p>
            </div>

            <div class="kpi-card kpi-slate">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(71,85,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#475569" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#475569; background:rgba(71,85,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Journées</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $cashBooks->count() }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0; text-transform:uppercase; letter-spacing:0.04em;">Journées ce mois</p>
            </div>
        </div>

        {{-- BANNIÈRE ACTION --}}
        <div class="info-banner a2">
            <div style="display:flex; align-items:flex-start; gap:12px;">
                <div style="width:36px; height:36px; border-radius:10px; background:rgba(29,27,132,0.08); display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;">
                    <svg width="17" height="17" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:13px; font-weight:800; color:#1D1B84; margin:0 0 3px;">Journée de caisse</p>
                    <p style="font-size:12px; color:#475569; font-weight:500; margin:0; line-height:1.5;">
                        Une fois la journée <strong style="color:#0f172a;">clôturée</strong>, les montants deviennent figés et aucune modification n'est possible.
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('cash.open_today') }}">
                @csrf
                <button type="submit" class="btn-success">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                    Ouvrir / Accéder à la caisse du jour
                </button>
            </form>
        </div>

        {{-- TABLEAU HISTORIQUE --}}
        <div class="section-card a3">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon" style="background:rgba(29,27,132,0.08);">
                        <svg width="17" height="17" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:14px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">Historique des caisses</p>
                        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:1px 0 0;">{{ $cashBooks->count() }} journée(s) enregistrée(s)</p>
                    </div>
                </div>
                <div style="display:flex; gap:8px; align-items:center;">
                    <span style="display:inline-flex; align-items:center; gap:5px; font-size:11px; font-weight:700; color:#15803d; background:#f0fdf4; border:1px solid #bbf7d0; padding:4px 11px; border-radius:99px;">
                        <span style="width:7px; height:7px; background:#16a34a; border-radius:50%; display:inline-block; box-shadow:0 0 6px rgba(22,163,74,0.5);"></span>
                        {{ $cashBooks->where('is_closed', false)->count() }} ouverte(s)
                    </span>
                    <span style="font-size:11px; font-weight:700; color:#475569; background:#f8fafc; border:1px solid #e2e8f0; padding:4px 11px; border-radius:99px;">
                        {{ $cashBooks->where('is_closed', true)->count() }} clôturée(s)
                    </span>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Centre</th>
                            <th>Solde veille</th>
                            <th>Entrées</th>
                            <th>Sorties</th>
                            <th>Solde final</th>
                            <th>Statut</th>
                            <th style="text-align:right; padding-right:22px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cashBooks as $book)
                            <tr>
                                <td>
                                    <div>
                                        <p style="font-size:13px; font-weight:800; color:#0f172a; margin:0; line-height:1.2;">
                                            {{ $book->date->translatedFormat('d/m/Y') }}
                                        </p>
                                        <p style="font-size:10px; color:#94a3b8; font-weight:600; margin:2px 0 0; text-transform:capitalize;">
                                            {{ $book->date->translatedFormat('l') }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size:12px; color:#475569; font-weight:600; background:#f8fafc; border:1px solid #e2e8f0; padding:3px 9px; border-radius:7px;">
                                        {{ $book->centre->name ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size:12.5px; color:#475569; font-weight:700;">
                                        {{ number_format($book->solde_veille, 0, ',', ' ') }}
                                        <span style="font-size:10px; font-weight:600; color:#94a3b8; margin-left:2px;">FCFA</span>
                                    </span>
                                </td>
                                <td>
                                    <span style="display:inline-flex; align-items:center; gap:5px; font-size:13px; font-weight:800; color:#15803d;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        </svg>
                                        {{ number_format($book->total_entrees, 0, ',', ' ') }}
                                        <span style="font-size:10px; font-weight:600; color:#86efac; margin-left:1px;">FCFA</span>
                                    </span>
                                </td>
                                <td>
                                    <span style="display:inline-flex; align-items:center; gap:5px; font-size:13px; font-weight:800; color:#be123c;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                        {{ number_format($book->total_sorties, 0, ',', ' ') }}
                                        <span style="font-size:10px; font-weight:600; color:#fca5a5; margin-left:1px;">FCFA</span>
                                    </span>
                                </td>
                                <td>
                                    <div style="background: {{ $book->solde_final >= 0 ? 'rgba(5,150,105,0.07)' : 'rgba(227,30,36,0.07)' }}; border:1px solid {{ $book->solde_final >= 0 ? 'rgba(5,150,105,0.2)' : 'rgba(227,30,36,0.2)' }}; border-radius:9px; padding:5px 11px; display:inline-block;">
                                        <span style="font-size:13px; font-weight:900; color:{{ $book->solde_final >= 0 ? '#065f46' : '#9f1239' }}; letter-spacing:-0.02em;">
                                            {{ number_format($book->solde_final, 0, ',', ' ') }}
                                        </span>
                                        <span style="font-size:9.5px; font-weight:700; color:{{ $book->solde_final >= 0 ? '#6ee7b7' : '#fca5a5' }}; margin-left:3px;">FCFA</span>
                                    </div>
                                </td>
                                <td>
                                    @if($book->is_closed)
                                        <span class="badge-closed">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Clôturée
                                        </span>
                                    @else
                                        <span class="badge-open">
                                            <span style="width:6px; height:6px; background:#16a34a; border-radius:50%; display:inline-block; box-shadow:0 0 6px rgba(22,163,74,0.6);"></span>
                                            Ouverte
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align:right; padding-right:22px;">
                                    <a href="{{ route('cash.show', $book) }}" class="link-voir">
                                        Voir
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div style="width:48px; height:48px; border-radius:14px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                                            <svg width="22" height="22" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                            </svg>
                                        </div>
                                        Aucune journée de caisse pour le moment.
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