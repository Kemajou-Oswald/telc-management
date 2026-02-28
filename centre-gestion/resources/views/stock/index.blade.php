<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Gestion de stock
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Produits, quantités disponibles et alertes de seuil
        </p>
    </x-slot>

    <style>
        /* ── KPI cards ── */
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
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(15,14,61,0.1);
        }
        .kpi-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 18px 18px 0 0;
        }
        .kpi-blue::after  { background: linear-gradient(90deg, #1D1B84, #4f46e5); }
        .kpi-green::after { background: linear-gradient(90deg, #059669, #34d399); }
        .kpi-red::after   { background: linear-gradient(90deg, #E31E24, #f97316); }
        .kpi-slate::after { background: linear-gradient(90deg, #475569, #94a3b8); }

        /* ── Section cards ── */
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-icon {
            width: 34px; height: 34px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* ── Form inputs ── */
        .erp-input {
            width: 100%;
            border: 1.5px solid rgba(15,14,61,0.1);
            border-radius: 11px;
            font-size: 13px;
            padding: 9px 13px;
            font-family: inherit;
            color: #0f172a;
            background: rgba(15,14,61,0.02);
            outline: none;
            transition: all 0.18s ease;
        }
        .erp-input::placeholder { color: #94a3b8; }
        .erp-input:focus {
            border-color: #1D1B84;
            background: white;
            box-shadow: 0 0 0 3px rgba(29,27,132,0.08);
        }
        .erp-label {
            display: block;
            font-size: 10.5px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 6px;
        }
        .erp-select {
            border: 1.5px solid rgba(15,14,61,0.1);
            border-radius: 9px;
            font-size: 12px;
            padding: 6px 10px;
            font-family: inherit;
            color: #334155;
            background: white;
            outline: none;
            cursor: pointer;
            transition: border-color 0.15s;
        }
        .erp-select:focus { border-color: #1D1B84; }

        /* ── Boutons ── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px;
            background: linear-gradient(135deg, #1D1B84 0%, #2d2bb0 100%);
            color: white;
            font-size: 12.5px;
            font-weight: 800;
            font-family: inherit;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 3px 14px rgba(29,27,132,0.3);
            white-space: nowrap;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(29,27,132,0.38);
        }
        .btn-sm {
            padding: 6px 13px;
            font-size: 11.5px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(29,27,132,0.22);
        }

        /* ── Table ── */
        .erp-table { width: 100%; border-collapse: collapse; }
        .erp-table thead tr {
            border-bottom: 2px solid #f1f5f9;
        }
        .erp-table thead th {
            padding: 10px 16px;
            font-size: 10px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            text-align: left;
            white-space: nowrap;
        }
        .erp-table tbody tr {
            border-bottom: 1px solid #f8fafc;
            transition: background 0.15s;
        }
        .erp-table tbody tr:hover { background: #fafbff; }
        .erp-table tbody tr:last-child { border-bottom: none; }
        .erp-table tbody td {
            padding: 13px 16px;
            vertical-align: middle;
        }

        /* ── Stock badge ── */
        .stock-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 800;
        }
        .stock-ok  { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .stock-low { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }

        /* ── Type badge ── */
        .type-in  { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:700; }
        .type-out { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:700; }
        .type-adj { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:700; }

        /* ── Empty state ── */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
        }

        /* ── Animation ── */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(10px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .a1 { animation: fadeUp 0.4s 0.05s cubic-bezier(0.22,1,0.36,1) both; }
        .a2 { animation: fadeUp 0.4s 0.12s cubic-bezier(0.22,1,0.36,1) both; }
        .a3 { animation: fadeUp 0.4s 0.20s cubic-bezier(0.22,1,0.36,1) both; }
        .a4 { animation: fadeUp 0.4s 0.28s cubic-bezier(0.22,1,0.36,1) both; }
    </style>

    <div style="display:flex; flex-direction:column; gap:22px; margin-top:4px;">

        {{-- ── Flash ── --}}
        @if(session('success'))
            <div style="display:flex; align-items:center; gap:10px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:12px 16px; font-size:13px; font-weight:700; color:#15803d;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── KPI ROW ── --}}
        <div class="a1" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px;">

            {{-- Total produits --}}
            <div class="kpi-card kpi-blue">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(29,27,132,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#1D1B84; background:rgba(29,27,132,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Total</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $products->count() }}
                </p>
                <p style="font-size:11.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Produits enregistrés</p>
            </div>

            {{-- En rupture / alerte --}}
            <div class="kpi-card kpi-red">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(227,30,36,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#E31E24" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#E31E24; background:rgba(227,30,36,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Alerte</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $products->filter(fn($p) => $p->min_stock && $p->currentStock() <= $p->min_stock)->count() }}
                </p>
                <p style="font-size:11.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Sous le seuil minimum</p>
            </div>

            {{-- Mouvements du jour --}}
            <div class="kpi-card kpi-green">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(5,150,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#059669" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#059669; background:rgba(5,150,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Aujourd'hui</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $movements->where('created_at', '>=', now()->startOfDay())->count() }}
                </p>
                <p style="font-size:11.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Mouvements aujourd'hui</p>
            </div>

            {{-- Historique total --}}
            <div class="kpi-card kpi-slate">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(71,85,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#475569" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#475569; background:rgba(71,85,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Historique</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $movements->count() }}
                </p>
                <p style="font-size:11.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Mouvements au total</p>
            </div>
        </div>

        {{-- ── FORMULAIRE NOUVEAU PRODUIT ── --}}
        <div class="section-card a2">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon" style="background:rgba(29,27,132,0.08);">
                        <svg width="17" height="17" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:14px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">Nouveau produit</p>
                        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:1px 0 0;">Ajouter un article au catalogue</p>
                    </div>
                </div>
            </div>

            <div style="padding:20px 22px;">
                <form method="POST" action="{{ route('stock.products.store') }}">
                    @csrf
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:14px; align-items:end;">
                        <div style="grid-column: span 2 / span 2;">
                            <label class="erp-label">Nom du produit</label>
                            <div style="position:relative;">
                                <svg style="position:absolute; left:11px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8;"
                                     width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <input type="text" name="name" class="erp-input" style="padding-left:34px;"
                                       placeholder="Ex : Cahier A4 100 pages" required>
                            </div>
                        </div>

                        <div>
                            <label class="erp-label">Référence / SKU</label>
                            <div style="position:relative;">
                                <svg style="position:absolute; left:11px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8;"
                                     width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <input type="text" name="sku" class="erp-input" style="padding-left:34px;"
                                       placeholder="Code interne (optionnel)">
                            </div>
                        </div>

                        <div>
                            <label class="erp-label">Stock minimum</label>
                            <div style="position:relative;">
                                <svg style="position:absolute; left:11px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8;"
                                     width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                </svg>
                                <input type="number" name="min_stock" min="0" class="erp-input" style="padding-left:34px;"
                                       placeholder="0">
                            </div>
                        </div>

                        <div style="display:flex; justify-content:flex-end;">
                            <button type="submit" class="btn-primary">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Ajouter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── TABLEAU PRODUITS ── --}}
        <div class="section-card a3">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon" style="background:rgba(29,27,132,0.08);">
                        <svg width="17" height="17" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:14px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">Produits en stock</p>
                        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:1px 0 0;">
                            {{ $products->count() }} article{{ $products->count() > 1 ? 's' : '' }} · enregistrez les mouvements ici
                        </p>
                    </div>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Centre</th>
                            <th>Stock actuel</th>
                            <th>Seuil min.</th>
                            <th style="text-align:right; padding-right:22px;">Enregistrer un mouvement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $stock = $product->currentStock();
                                $low   = $product->min_stock && $stock <= $product->min_stock;
                            @endphp
                            <tr>
                                {{-- Produit --}}
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:34px; height:34px; border-radius:10px; background:{{ $low ? 'rgba(227,30,36,0.07)' : 'rgba(29,27,132,0.06)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg width="15" height="15" fill="none" stroke="{{ $low ? '#E31E24' : '#1D1B84' }}" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p style="font-size:13px; font-weight:800; color:#0f172a; margin:0; line-height:1.2;">{{ $product->name }}</p>
                                            @if($product->sku)
                                                <span style="font-size:10px; color:#94a3b8; font-weight:600; background:#f8fafc; border:1px solid #e2e8f0; padding:1px 6px; border-radius:5px; margin-top:3px; display:inline-block;">
                                                    {{ $product->sku }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Centre --}}
                                <td>
                                    <span style="font-size:12px; color:#475569; font-weight:600;">
                                        {{ $product->centre->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Stock actuel --}}
                                <td>
                                    <span class="stock-badge {{ $low ? 'stock-low' : 'stock-ok' }}">
                                        @if($low)
                                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                            </svg>
                                        @else
                                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                        {{ $stock }} {{ $product->unit }}
                                    </span>
                                </td>

                                {{-- Seuil --}}
                                <td>
                                    <span style="font-size:12px; color:#94a3b8; font-weight:600;">
                                        {{ $product->min_stock ? $product->min_stock.' '.$product->unit : '—' }}
                                    </span>
                                </td>

                                {{-- Mouvement inline --}}
                                <td style="text-align:right; padding-right:22px;">
                                    <form method="POST" action="{{ route('stock.movements.store', $product) }}"
                                          style="display:inline-flex; align-items:center; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                                        @csrf
                                        <select name="type" class="erp-select">
                                            <option value="in">↑ Entrée</option>
                                            <option value="out">↓ Sortie</option>
                                        </select>
                                        <input type="number" name="quantity" min="1"
                                               class="erp-input" style="width:68px; text-align:center;"
                                               placeholder="Qté" required>
                                        <input type="text" name="label"
                                               class="erp-input" style="width:120px;"
                                               placeholder="Motif">
                                        <button type="submit" class="btn-primary btn-sm">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Valider
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div style="width:48px; height:48px; border-radius:14px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                                            <svg width="22" height="22" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        Aucun produit enregistré — ajoutez-en un ci-dessus.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── HISTORIQUE MOUVEMENTS ── --}}
        <div class="section-card a4">
            <div class="section-header">
                <div class="section-title">
                    <div class="section-icon" style="background:rgba(71,85,105,0.08);">
                        <svg width="17" height="17" fill="none" stroke="#475569" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:14px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.02em;">Historique des mouvements</p>
                        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:1px 0 0;">Toutes les entrées et sorties</p>
                    </div>
                </div>
                <span style="font-size:11px; font-weight:800; color:#475569; background:#f1f5f9; border:1px solid #e2e8f0; padding:4px 12px; border-radius:99px; letter-spacing:0.06em;">
                    {{ $movements->count() }} ligne{{ $movements->count() > 1 ? 's' : '' }}
                </span>
            </div>

            <div style="overflow-x:auto;">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Centre</th>
                            <th>Par</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $move)
                            <tr>
                                {{-- Date --}}
                                <td>
                                    <div>
                                        <p style="font-size:12.5px; font-weight:700; color:#334155; margin:0;">
                                            {{ $move->created_at->format('d/m/Y') }}
                                        </p>
                                        <p style="font-size:10.5px; color:#94a3b8; font-weight:600; margin:1px 0 0;">
                                            {{ $move->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Produit --}}
                                <td>
                                    <span style="font-size:13px; font-weight:700; color:#0f172a;">
                                        {{ $move->product->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Type --}}
                                <td>
                                    @if($move->type === 'in')
                                        <span class="type-in" style="display:inline-flex; align-items:center; gap:4px;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                            Entrée
                                        </span>
                                    @elseif($move->type === 'out')
                                        <span class="type-out" style="display:inline-flex; align-items:center; gap:4px;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                            Sortie
                                        </span>
                                    @else
                                        <span class="type-adj" style="display:inline-flex; align-items:center; gap:4px;">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Ajustement
                                        </span>
                                    @endif
                                </td>

                                {{-- Quantité --}}
                                <td>
                                    <span style="font-size:14px; font-weight:900; color:{{ $move->type === 'out' ? '#be123c' : '#15803d' }}; letter-spacing:-0.02em;">
                                        {{ $move->type === 'out' ? '−' : '+' }}{{ $move->quantity }}
                                    </span>
                                </td>

                                {{-- Centre --}}
                                <td>
                                    <span style="font-size:12px; color:#475569; font-weight:600;">
                                        {{ $move->centre->name ?? '—' }}
                                    </span>
                                </td>

                                {{-- Par --}}
                                <td>
                                    @if($move->creator)
                                        <div style="display:flex; align-items:center; gap:7px;">
                                            <div style="width:26px; height:26px; border-radius:8px; background:linear-gradient(135deg,#E31E24,#1D1B84); display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:900; color:white; flex-shrink:0;">
                                                {{ strtoupper(substr($move->creator->name,0,1)) }}
                                            </div>
                                            <span style="font-size:12px; color:#334155; font-weight:700;">{{ $move->creator->name }}</span>
                                        </div>
                                    @else
                                        <span style="font-size:12px; color:#94a3b8;">—</span>
                                    @endif
                                </td>

                                {{-- Motif --}}
                                <td>
                                    @if($move->label)
                                        <span style="font-size:12px; color:#475569; font-weight:600; background:#f8fafc; border:1px solid #e2e8f0; padding:3px 9px; border-radius:7px;">
                                            {{ $move->label }}
                                        </span>
                                    @else
                                        <span style="font-size:12px; color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div style="width:48px; height:48px; border-radius:14px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                                            <svg width="22" height="22" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        Aucun mouvement enregistré pour l'instant.
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