<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re&#231;u {{ $registration->receipt_number }} &#8212; Tara Institut et Der Beste Betreuer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:       #1A1A1A;
            --red:        #C41B1B;
            --white:      #FFFFFF;
            --bg:         #F5F5F5;
            --border:     #E3E3E3;
            --text:       #1A1A1A;
            --muted:      #7A7A7A;
            --success:    #15803D;
            --success-bg: #F0FDF4;
            --warning:    #B45309;
            --warning-bg: #FFFBEB;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: var(--text);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* ── Action bar (masquee a l'impression) ── */
        .action-bar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .action-bar-left  { display: flex; align-items: center; gap: 8px; }
        .action-bar-right { display: flex; align-items: center; gap: 8px; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0 15px; height: 36px; border-radius: 8px;
            font: inherit; font-size: 12.5px; font-weight: 500;
            cursor: pointer; border: none; text-decoration: none;
            transition: all 120ms; white-space: nowrap;
        }
        .btn svg { flex-shrink: 0; }
        .btn-primary   { background: var(--red);   color: #fff; }
        .btn-primary:hover   { background: #a81717; }
        .btn-secondary { background: var(--white); color: var(--navy); border: 1px solid var(--border); }
        .btn-secondary:hover { background: #EEF1FA; }
        .btn-ghost     { background: transparent;  color: var(--muted); border: 1px solid var(--border); }
        .btn-ghost:hover     { background: var(--white); color: var(--navy); }

        /* ── Feuille ── */
        .paper {
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 0;
            overflow: hidden;
            min-height: 100vh;
            box-shadow: none;
        }

        /* En-tete sombre */
        .receipt-header {
            background: var(--navy);
            padding: 15px 25px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            position: relative;
            overflow: hidden;
        }
        .receipt-header::after {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196,27,27,0.18) 0%, transparent 65%);
            pointer-events: none;
        }

        .receipt-brand { position: relative; z-index: 1; }
        .receipt-brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 15px; font-weight: 700;
            color: #fff; letter-spacing: 0.03em; margin-bottom: 2px;
        }
        .receipt-brand-sub {
            font-size: 10px; color: rgba(255,255,255,0.4);
            text-transform: uppercase; letter-spacing: 0.08em;
        }

        .receipt-meta { position: relative; z-index: 1; text-align: right; }
        .receipt-label {
            font-size: 10px; color: rgba(255,255,255,0.4);
            text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px;
        }
        .receipt-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px; font-weight: 700; color: #fff;
            margin-bottom: 6px;
        }
        .receipt-number {
            font-size: 12px; color: rgba(255,255,255,0.55);
            font-variant-numeric: tabular-nums; letter-spacing: 0.04em;
        }

        .receipt-status {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 100px;
            font-size: 10.5px; font-weight: 600;
            margin-top: 8px;
        }
        .receipt-status::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
        .receipt-status.paid    { background: rgba(21,128,61,0.25); color: #6ee7a0; }
        .receipt-status.paid::before    { background: #6ee7a0; }
        .receipt-status.pending { background: rgba(180,83,9,0.25);  color: #fbbf24; }
        .receipt-status.pending::before { background: #fbbf24; }

        /* Corps */
        .receipt-body { 
            padding: 15px 25px; 
        }

        /* Separateur */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 10px 0;
        }
        .divider hr { flex: 1; border: none; border-top: 1px solid var(--border); }
        .divider span {
            font-size: 9.5px; font-weight: 600;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--muted); white-space: nowrap;
        }

        /* Grille infos */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 4px;
        }
        .info-block {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #FAFBFD;
        }
        .info-block.full      { grid-column: 1 / -1; }
        .info-block.highlight { background: var(--navy); border-color: var(--navy); }

        .info-label {
            font-size: 8.5px; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 4px;
        }
        .info-block.highlight .info-label { color: rgba(255,255,255,0.45); }

        .info-value {
            font-size: 12px; color: var(--text); font-weight: 500;
            line-height: 1.3;
        }
        .info-value .sub {
            display: block;
            font-size: 10px; font-weight: 400; color: var(--muted);
            margin-top: 1px;
        }
        .info-block.highlight .info-value       { color: #fff; }
        .info-block.highlight .info-value .sub  { color: rgba(255,255,255,0.45); }

        .info-amount {
            font-family: 'Syne', sans-serif;
            font-size: 24px; font-weight: 700; color: #fff;
            letter-spacing: 0.02em; line-height: 1;
        }
        .info-amount-unit { font-size: 13px; font-weight: 400; color: rgba(255,255,255,0.5); margin-left: 4px; }

        /* Badge */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 2px 8px; border-radius: 5px;
            font-size: 11px; font-weight: 500;
        }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
        .badge.success { background: var(--success-bg); color: var(--success); }
        .badge.success::before { background: var(--success); }
        .badge.warning { background: var(--warning-bg); color: var(--warning); }
        .badge.warning::before { background: var(--warning); }

        /* Pied de feuille */
        .receipt-footer {
            padding: 10px 25px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FAFBFD;
        }
        .receipt-footer-note {
            font-size: 10.5px; color: var(--muted); line-height: 1.5;
        }
        .receipt-footer-note strong { color: var(--navy); }

        /* Zone de signature */
        .stamp-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 8px;
        }
        .stamp-box {
            border: 1px dashed var(--border);
            border-radius: 8px;
            padding: 8px;
            min-height: 70px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .stamp-label {
            font-size: 10px; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--muted);
        }
        .stamp-space {
            border-top: 1px solid var(--border);
            margin-top: 18px;
        }

        /* ── Impression ── */
        @media print {
            body { 
                background: #fff; 
                padding: 0; 
                margin: 0;
            }
            .action-bar { display: none !important; }
            .paper { 
                box-shadow: none; 
                border-radius: 0; 
                border: 1px solid #ccc; 
                width: 100%; 
                max-width: none;
                margin: 0;
                min-height: auto;
                page-break-inside: avoid;
            }
            .receipt-header { 
                background: #1a1a1a !important; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
            .receipt-footer { 
                page-break-inside: avoid; 
                background: #f9fbfd !important;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
            .info-block.highlight { 
                background: #1a1a1a !important; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
            .badge.success { 
                background: #f0fdf4 !important; 
                color: #15803d !important;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
            .badge.warning { 
                background: #fffbec !important; 
                color: #b45309 !important;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
        }

        @media (max-width: 600px) {
            .stamp-grid    { grid-template-columns: 1fr; }
            .info-grid     { grid-template-columns: 1fr; }
            .receipt-header { flex-direction: column; gap: 14px; }
            .receipt-meta  { text-align: left; }
            .receipt-body  { padding: 20px; }
            .receipt-footer { flex-direction: column; gap: 10px; align-items: flex-start; }
        }
    </style>
</head>
<body>

    <!-- Barre d'actions -->
    <div class="action-bar">
        <div class="action-bar-left">
            <a class="btn btn-ghost" href="{{ route('registrations.index') }}" onclick="if (window.history.length > 1) { window.history.back(); return false; }">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Retour
            </a>
        </div>
        <div class="action-bar-right">
            @if ($registration->payment_status === 'paid')
                <button class="btn btn-primary" onclick="generatePDF(); return false;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"/>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    Générer PDF
                </button>
            @else
                <button class="btn btn-primary" disabled title="Le reçu sera disponible après paiement">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"/>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    Générer PDF
                </button>
            @endif
            @if (($user->isSecretary() || $user->isCenterAdmin()) && $registration->status === 'active')
                <a class="btn btn-secondary" href="{{ route('registrations.edit', $registration) }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Corriger
                </a>
            @endif
        </div>
    </div>

    <!-- Feuille -->
    <div class="paper">

        <!-- En-tete -->
        <div class="receipt-header">
            <div class="receipt-brand">
                <div class="receipt-brand-name">Tara Institut et Der Beste Betreuer</div>
                <div class="receipt-brand-sub">Pr&#233;inscription TELC</div>
                <div style="margin-top:10px; font-size:11px; color:rgba(255,255,255,0.45); line-height:1.7;">
                    Centre : {{ optional($registration->center)->name }}<br>
                    Date : {{ optional($registration->registered_at)->format('d/m/Y à H:i') }}
                </div>
            </div>
            <div class="receipt-meta">
                <div class="receipt-label">Document</div>
                <div class="receipt-title">Re&#231;u d&#8217;inscription</div>
                <div class="receipt-number">N&#176;&#160;{{ $registration->receipt_number }}</div>
                <div>
                    @if ($registration->payment_status === 'paid')
                        <span class="receipt-status paid">Pay&#233;</span>
                    @else
                        <span class="receipt-status pending">En attente de paiement</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Corps -->
        <div class="receipt-body">

            <div class="divider"><hr><span>&#201;tudiant</span><hr></div>

            <div class="info-grid">
                <div class="info-block">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value">
                        {{ optional($registration->student)->full_name }}
                        <span class="sub">{{ optional($registration->student)->email ?? '&#8212;' }}</span>
                    </div>
                </div>
                <div class="info-block">
                    <div class="info-label">Centre</div>
                    <div class="info-value">
                        {{ optional($registration->center)->name }}
                    </div>
                </div>
                <div class="info-block full">
                    <div class="info-label">Enregistre par</div>
                    <div class="info-value">
                        {{ optional($registration->registrar)->name ?: '&#8212;' }}
                        <span class="sub">{{ optional($registration->registered_at)->format('d/m/Y H:i') ?? '&#8212;' }}</span>
                    </div>
                </div>
            </div>

            <div class="divider"><hr><span>Session d&#8217;examen</span><hr></div>

            <div class="info-grid">
                <div class="info-block">
                    <div class="info-label">Session</div>
                    <div class="info-value">
                        {{ optional($registration->examSession)->title }}
                        <span class="sub">{{ optional($registration->examSession)->level }} - {{ optional($registration->examSession)->city ?? '&#8212;' }}</span>
                    </div>
                </div>
                <div class="info-block">
                    <div class="info-label">Date examen</div>
                    <div class="info-value">
                        {{ optional(optional($registration->examSession)->exam_date)->format('d/m/Y') ?? '&#8212;' }}
                    </div>
                </div>
            </div>

            <div class="divider"><hr><span>Paiement</span><hr></div>

            <div class="info-grid">
                <div class="info-block highlight">
                    <div class="info-label">Montant d&#251;</div>
                    <div class="info-value">
                        <span class="info-amount">
                            {{ number_format($registration->amount, 0, ',', ' ') }}<span class="info-amount-unit">FCFA</span>
                        </span>
                    </div>
                </div>
                <div class="info-block">
                    <div class="info-label">Statut</div>
                    <div class="info-value" style="margin-top:4px;">
                        @if ($registration->payment_status === 'paid')
                            <span class="badge success">Pay&#233;</span>
                        @else
                            <span class="badge warning">En attente</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="divider"><hr><span>Validation</span><hr></div>

            <div class="stamp-grid">
                <div class="stamp-box">
                    <div class="stamp-label">Cachet du centre</div>
                    <div class="stamp-space"></div>
                </div>
                <div class="stamp-box">
                    <div class="stamp-label">Signature du chef de centre</div>
                    <div class="stamp-space"></div>
                </div>
            </div>

        </div>

        <!-- Pied de feuille -->
        <div class="receipt-footer">
            <div class="receipt-footer-note">
                <strong>Tara Institut et Der Beste Betreuer</strong> &#8212; Pr&#233;inscription TELC<br>
                Ce document est g&#233;n&#233;r&#233; automatiquement et fait foi de l&#8217;inscription.
            </div>
            <div style="font-size:10.5px; color:var(--muted); font-variant-numeric:tabular-nums;">
                R&#233;f.&#160;{{ $registration->receipt_number }}
            </div>
        </div>

    </div>

    <script>
        function generatePDF() {
            // Créer une fenêtre d'impression optimisée pour PDF
            const printWindow = window.open('', '_blank');
            
            // Copier le contenu HTML de la feuille
            const paperContent = document.querySelector('.paper').cloneNode(true);
            
            // Créer le HTML pour l'impression
            const printHTML = `
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Reçu {{ $registration->receipt_number }}</title>
                    <style>
                        @page {
                            size: A4 portrait;
                            margin: 15mm;
                        }
                        
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        
                        body {
                            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                            background: #fff;
                            color: #1a1a1a;
                            font-size: 13px;
                            line-height: 1.5;
                        }
                        
                        .paper {
                            width: 100%;
                            background: #fff;
                            border: 1px solid #ccc;
                            overflow: hidden;
                            page-break-inside: avoid;
                        }
                        
                        .receipt-header {
                            background: #1a1a1a;
                            padding: 28px 36px;
                            display: flex;
                            align-items: flex-start;
                            justify-content: space-between;
                            gap: 20px;
                            position: relative;
                            overflow: hidden;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        
                        .receipt-header::after {
                            content: '';
                            position: absolute;
                            top: -80px; right: -80px;
                            width: 220px; height: 220px;
                            border-radius: 50%;
                            background: radial-gradient(circle, rgba(196,27,27,0.18) 0%, transparent 65%);
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        
                        .receipt-brand { position: relative; z-index: 1; }
                        .receipt-brand-name {
                            font-family: 'Syne', sans-serif;
                            font-size: 15px; font-weight: 700;
                            color: #fff; letter-spacing: 0.03em; margin-bottom: 2px;
                        }
                        .receipt-brand-sub {
                            font-size: 10px; color: rgba(255,255,255,0.4);
                            text-transform: uppercase; letter-spacing: 0.08em;
                        }
                        
                        .receipt-meta { position: relative; z-index: 1; text-align: right; }
                        .receipt-label {
                            font-size: 10px; color: rgba(255,255,255,0.4);
                            text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px;
                        }
                        .receipt-title {
                            font-family: 'Syne', sans-serif;
                            font-size: 20px; font-weight: 700; color: #fff;
                            margin-bottom: 6px;
                        }
                        .receipt-number {
                            font-size: 12px; color: rgba(255,255,255,0.55);
                            font-variant-numeric: tabular-nums; letter-spacing: 0.04em;
                        }
                        
                        .receipt-status {
                            display: inline-flex; align-items: center; gap: 5px;
                            padding: 4px 10px; border-radius: 100px;
                            font-size: 10.5px; font-weight: 600;
                            margin-top: 8px;
                        }
                        .receipt-status::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
                        .receipt-status.paid { background: rgba(21,128,61,0.25); color: #6ee7a0; }
                        .receipt-status.paid::before { background: #6ee7a0; }
                        .receipt-status.pending { background: rgba(180,83,9,0.25); color: #fbbf24; }
                        .receipt-status.pending::before { background: #fbbf24; }
                        
                        .receipt-body { padding: 28px 36px; }
                        
                        .divider {
                            display: flex; align-items: center; gap: 12px;
                            margin: 20px 0;
                        }
                        .divider hr { flex: 1; border: none; border-top: 1px solid #e3e3e3; }
                        .divider span {
                            font-size: 9.5px; font-weight: 600;
                            letter-spacing: 0.12em; text-transform: uppercase;
                            color: #7a7a7a; white-space: nowrap;
                        }
                        
                        .info-grid {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 10px;
                            margin-bottom: 4px;
                        }
                        .info-block {
                            padding: 14px 16px;
                            border-radius: 10px;
                            border: 1px solid #e3e3e3;
                            background: #fafbfd;
                        }
                        .info-block.full { grid-column: 1 / -1; }
                        .info-block.highlight { 
                            background: #1a1a1a; 
                            border-color: #1a1a1a;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        
                        .info-label {
                            font-size: 9.5px; font-weight: 600;
                            letter-spacing: 0.1em; text-transform: uppercase;
                            color: #7a7a7a; margin-bottom: 6px;
                        }
                        .info-block.highlight .info-label { color: rgba(255,255,255,0.45); }
                        
                        .info-value {
                            font-size: 13.5px; color: #1a1a1a; font-weight: 500;
                            line-height: 1.5;
                        }
                        .info-value .sub {
                            display: block;
                            font-size: 12px; font-weight: 400; color: #7a7a7a;
                            margin-top: 1px;
                        }
                        .info-block.highlight .info-value { color: #fff; }
                        .info-block.highlight .info-value .sub { color: rgba(255,255,255,0.45); }
                        
                        .info-amount {
                            font-family: 'Syne', sans-serif;
                            font-size: 28px; font-weight: 700; color: #fff;
                            letter-spacing: 0.02em; line-height: 1;
                        }
                        .info-amount-unit { font-size: 13px; font-weight: 400; color: rgba(255,255,255,0.5); margin-left: 4px; }
                        
                        .badge {
                            display: inline-flex; align-items: center; gap: 4px;
                            padding: 2px 8px; border-radius: 5px;
                            font-size: 11px; font-weight: 500;
                        }
                        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
                        .badge.success { 
                            background: #f0fdf4; 
                            color: #15803d;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        .badge.success::before { background: #15803d; }
                        .badge.warning { 
                            background: #fffbec; 
                            color: #b45309;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        .badge.warning::before { background: #b45309; }
                        
                        .receipt-footer {
                            padding: 16px 36px;
                            border-top: 1px solid #e3e3e3;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            background: #fafbfd;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        .receipt-footer-note {
                            font-size: 10.5px; color: #7a7a7a; line-height: 1.5;
                        }
                        .receipt-footer-note strong { color: #1a1a1a; }
                        
                        .stamp-grid {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 16px;
                            margin-top: 12px;
                        }
                        .stamp-box {
                            border: 1px dashed #e3e3e3;
                            border-radius: 12px;
                            padding: 12px;
                            min-height: 110px;
                            display: flex;
                            flex-direction: column;
                            justify-content: space-between;
                            page-break-inside: avoid;
                        }
                        .stamp-label {
                            font-size: 10px; font-weight: 600;
                            letter-spacing: 0.1em; text-transform: uppercase;
                            color: #7a7a7a;
                        }
                        .stamp-space {
                            border-top: 1px solid #e3e3e3;
                            margin-top: 36px;
                        }
                    </style>
                </head>
                <body>
                    ${paperContent.outerHTML}
                </body>
                </html>
            `;
            
            // Écrire le contenu dans la fenêtre d'impression
            printWindow.document.write(printHTML);
            printWindow.document.close();
            
            // Attendre que le contenu soit chargé puis imprimer
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            };
        }
    </script>

</body>
</html>
