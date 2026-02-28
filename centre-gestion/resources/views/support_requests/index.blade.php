<x-app-layout>
    <x-slot name="header">
        <h1 style="font-size:18px; font-weight:900; color:#0f172a; margin:0; letter-spacing:-0.03em;">
            Réclamations & demandes
        </h1>
        <p style="font-size:11px; color:#94a3b8; font-weight:600; margin:2px 0 0;">
            Communication Secrétaire → Direction / Super Admin
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
        .kpi-blue::after   { background:linear-gradient(90deg,#1D1B84,#4f46e5); }
        .kpi-amber::after  { background:linear-gradient(90deg,#d97706,#fbbf24); }
        .kpi-green::after  { background:linear-gradient(90deg,#059669,#34d399); }
        .kpi-red::after    { background:linear-gradient(90deg,#E31E24,#f97316); }

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

        /* ── Inputs ── */
        .erp-label {
            display:block; font-size:10.5px; font-weight:800;
            color:#64748b; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;
        }
        .erp-input {
            width:100%; border:1.5px solid rgba(15,14,61,0.1); border-radius:11px;
            font-size:13px; padding:10px 13px; font-family:inherit;
            color:#0f172a; background:rgba(15,14,61,0.02); outline:none;
            transition:all 0.18s ease; box-sizing:border-box;
        }
        .erp-input::placeholder { color:#94a3b8; }
        .erp-input:focus { border-color:#1D1B84; background:white; box-shadow:0 0 0 3px rgba(29,27,132,0.08); }
        .erp-input-error { border-color:#E31E24 !important; }
        .erp-select {
            width:100%; border:1.5px solid rgba(15,14,61,0.1); border-radius:11px;
            font-size:13px; padding:10px 13px; font-family:inherit;
            color:#0f172a; background:white; outline:none;
            transition:all 0.18s; cursor:pointer; box-sizing:border-box;
        }
        .erp-select:focus { border-color:#1D1B84; box-shadow:0 0 0 3px rgba(29,27,132,0.08); }
        .erp-textarea {
            width:100%; border:1.5px solid rgba(15,14,61,0.1); border-radius:11px;
            font-size:13px; padding:10px 13px; font-family:inherit;
            color:#0f172a; background:rgba(15,14,61,0.02); outline:none;
            transition:all 0.18s; resize:vertical; box-sizing:border-box; line-height:1.6;
        }
        .erp-textarea::placeholder { color:#94a3b8; }
        .erp-textarea:focus { border-color:#1D1B84; background:white; box-shadow:0 0 0 3px rgba(29,27,132,0.08); }

        /* ── Boutons ── */
        .btn-primary {
            display:inline-flex; align-items:center; gap:7px;
            padding:10px 20px;
            background:linear-gradient(135deg,#1D1B84 0%,#2d2bb0 100%);
            color:white; font-size:13px; font-weight:800; font-family:inherit;
            border:none; border-radius:11px; cursor:pointer;
            transition:all 0.2s; box-shadow:0 3px 14px rgba(29,27,132,0.3);
        }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(29,27,132,0.38); }

        .btn-resolve {
            display:inline-flex; align-items:center; gap:6px;
            padding:7px 14px; font-size:11.5px; font-weight:800; font-family:inherit;
            color:#065f46; background:rgba(5,150,105,0.08);
            border:1.5px solid rgba(5,150,105,0.2); border-radius:99px;
            cursor:pointer; transition:all 0.15s;
        }
        .btn-resolve:hover { background:rgba(5,150,105,0.14); border-color:rgba(5,150,105,0.35); transform:translateY(-1px); }

        /* ── Table ── */
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
        .erp-table tbody td { padding:14px 16px; vertical-align:middle; }

        /* ── Badges statut ── */
        .badge-pending {
            display:inline-flex; align-items:center; gap:5px;
            background:rgba(217,119,6,0.08); color:#92400e;
            border:1px solid rgba(217,119,6,0.2); padding:4px 11px;
            border-radius:99px; font-size:11px; font-weight:800;
        }
        .badge-resolved {
            display:inline-flex; align-items:center; gap:5px;
            background:rgba(5,150,105,0.08); color:#065f46;
            border:1px solid rgba(5,150,105,0.2); padding:4px 11px;
            border-radius:99px; font-size:11px; font-weight:800;
        }

        /* ── Badges catégorie ── */
        .cat-stock     { background:rgba(29,27,132,0.07); color:#1D1B84;  border:1px solid rgba(29,27,132,0.15); }
        .cat-finance   { background:rgba(5,150,105,0.07); color:#065f46;  border:1px solid rgba(5,150,105,0.15); }
        .cat-technique { background:rgba(124,58,237,0.07);color:#6d28d9;  border:1px solid rgba(124,58,237,0.15);}
        .cat-autre     { background:rgba(71,85,105,0.07);  color:#334155;  border:1px solid rgba(71,85,105,0.15); }
        .cat-badge {
            font-size:10.5px; font-weight:800; padding:3px 9px;
            border-radius:99px; display:inline-flex; align-items:center; gap:5px;
        }

        .flash-success {
            display:flex; align-items:center; gap:10px;
            background:#f0fdf4; border:1px solid #bbf7d0;
            border-radius:12px; padding:12px 16px;
            font-size:13px; font-weight:700; color:#15803d;
        }
        .flash-info {
            display:flex; align-items:center; gap:10px;
            background:#f8fafc; border:1px solid #e2e8f0;
            border-radius:12px; padding:12px 16px;
            font-size:13px; font-weight:700; color:#475569;
        }
        .empty-state { padding:56px 24px; text-align:center; color:#94a3b8; font-size:13px; font-weight:600; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .a1{animation:fadeUp 0.4s 0.05s cubic-bezier(0.22,1,0.36,1) both}
        .a2{animation:fadeUp 0.4s 0.12s cubic-bezier(0.22,1,0.36,1) both}
        .a3{animation:fadeUp 0.4s 0.20s cubic-bezier(0.22,1,0.36,1) both}
    </style>

    <div style="display:flex; flex-direction:column; gap:22px; margin-top:4px;">

        @if(session('success'))
            <div class="flash-success a1">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- KPI ROW --}}
        <div class="a1" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:14px;">
            <div class="kpi-card kpi-blue">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(29,27,132,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#1D1B84" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#1D1B84; background:rgba(29,27,132,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Total</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">{{ $requests->total() }}</p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Demandes au total</p>
            </div>

            <div class="kpi-card kpi-amber">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(217,119,6,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#d97706" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#d97706; background:rgba(217,119,6,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Attente</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $requests->getCollection()->where('status','!=','resolu')->count() }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">En attente</p>
            </div>

            <div class="kpi-card kpi-green">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div style="width:38px; height:38px; border-radius:11px; background:rgba(5,150,105,0.08); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#059669" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span style="font-size:9px; font-weight:800; color:#059669; background:rgba(5,150,105,0.08); padding:3px 8px; border-radius:99px; letter-spacing:0.1em; text-transform:uppercase;">Résolus</span>
                </div>
                <p style="font-size:28px; font-weight:900; color:#0f172a; margin:0; line-height:1; letter-spacing:-0.03em;">
                    {{ $requests->getCollection()->where('status','resolu')->count() }}
                </p>
                <p style="font-size:10.5px; color:#64748b; font-weight:600; margin:4px 0 0;">Demandes résolues</p>
            </div>
        </div>

        {{-- FORMULAIRE --}}
        @if(auth()->user()->role === 'secretaire')
        <div class="section-card a2">
            <div style="padding:22px 24px;">
                <form method="POST" action="{{ route('support_requests.store') }}">
                    @csrf
                    <div class="grid-2" style="display:grid; grid-template-columns:160px 1fr; gap:14px; margin-bottom:14px;">
                        <div>
                            <label class="erp-label">Type</label>
                            <select name="category" class="erp-select">
                                <option value="stock">Stock</option>
                                <option value="finance">Finance</option>
                                <option value="technique">Technique</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="erp-label">Objet</label>
                            <input type="text" name="title" class="erp-input" placeholder="Objet de la demande" required>
                        </div>
                    </div>
                    <div style="margin-bottom:18px;">
                        <label class="erp-label">Description</label>
                        <textarea name="description" rows="3" class="erp-textarea" placeholder="Détails..."></textarea>
                    </div>
                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn-primary">Envoyer la demande</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- TABLEAU --}}
        <div class="section-card a3">
            <div style="overflow-x:auto;">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Objet</th>
                            <th>Centre</th>
                            <th>Statut</th>
                            <th style="text-align:right; padding-right:22px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td>{{ $req->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        // Correction du match ici pour compatibilité PHP < 8.0
                                        $configs = [
                                            'stock' => ['class' => 'cat-stock', 'label' => 'Stock', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                                            'finance' => ['class' => 'cat-finance', 'label' => 'Finance', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                                            'technique' => ['class' => 'cat-technique', 'label' => 'Technique', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                                        ];
                                        $catConfig = isset($configs[$req->category]) ? $configs[$req->category] : ['class' => 'cat-autre', 'label' => 'Autre', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
                                    @endphp
                                    <span class="cat-badge {{ $catConfig['class'] }}">
                                        {{ $catConfig['label'] }}
                                    </span>
                                </td>
                                <td>{{ $req->title }}</td>
                                <td>{{ optional($req->centre)->name ?? '—' }}</td>
                                <td>
                                    @if($req->status === 'resolu')
                                        <span class="badge-resolved">Résolu</span>
                                    @else
                                        <span class="badge-pending">En attente</span>
                                    @endif
                                </td>
                                <td style="text-align:right; padding-right:22px;">
                                    @if($req->status !== 'resolu' && in_array(auth()->user()->role, ['super_admin','directeur']))
                                        <form method="POST" action="{{ route('support_requests.resolve', $req) }}">
                                            @csrf
                                            <button type="submit" class="btn-resolve">Résoudre</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty-state">Aucune demande.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>