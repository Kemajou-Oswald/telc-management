<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Reçu {{ $registration->receipt_number }}</title>
    <style>
        @page { size: A4; margin: 14mm 16mm 14mm 16mm; }

        * { margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
            color: #1a1a1a;
            background: #ffffff;
        }

        /* ════════════════════════
           EN-TETE SOMBRE
        ════════════════════════ */
        .hd-table {
            width: 100%;
            background-color: #1a1a1a;
            border-collapse: separate;
            border-spacing: 0;
        }
        .hd-table td {
            padding: 20px 22px;
            vertical-align: top;
        }
        .hd-right { text-align: right; width: 195px; }

        .org-name { font-size: 12.5pt; font-weight: bold; color: #ffffff; }
        .org-sub  { font-size: 7.5pt; color: #999999; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 3px; }
        .org-meta { margin-top: 10px; font-size: 8.5pt; color: #aaaaaa; line-height: 1.7; }

        .doc-lbl   { font-size: 7.5pt; color: #999999; text-transform: uppercase; letter-spacing: 0.1em; }
        .doc-title { font-size: 16pt; font-weight: bold; color: #ffffff; margin-top: 3px; margin-bottom: 5px; }
        .doc-num   { font-size: 9pt; color: #aaaaaa; margin-bottom: 8px; }

        .pill-paid {
            display: inline-block; padding: 3px 10px;
            font-size: 8pt; font-weight: bold;
            color: #22c55e; background-color: #14532d; border: 1px solid #166534;
        }
        .pill-wait {
            display: inline-block; padding: 3px 10px;
            font-size: 8pt; font-weight: bold;
            color: #fbbf24; background-color: #78350f; border: 1px solid #92400e;
        }

        /* ════════════════════════
           SEPARATEURS
        ════════════════════════ */
        .sep {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 13px;
            margin-bottom: 7px;
        }
        .sep td { vertical-align: middle; }
        .sep-line { border-top: 1px solid #dddddd; }
        .sep-txt  {
            font-size: 7pt; font-weight: bold;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: #888888; white-space: nowrap;
            padding: 0 9px; text-align: center;
        }

        /* ════════════════════════
           GRILLES DE BLOCS
           border-collapse: SEPARATE
           sinon les bordures des td disparaissent
        ════════════════════════ */
        .grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .grid td { vertical-align: top; }
        .gap-col { width: 7px; }

        /* Bloc clair */
        .blk {
            width: 46.5%;
            padding: 11px 13px;
            background-color: #f9fbfd;
            border: 1px solid #dddddd;
        }
        /* Bloc foncé */
        .blk-dark {
            width: 46.5%;
            padding: 11px 13px;
            background-color: #1a1a1a;
            border: 1px solid #1a1a1a;
        }

        .lbl {
            font-size: 7pt; font-weight: bold;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: #888888; margin-bottom: 5px;
        }
        .blk-dark .lbl { color: #777777; }

        .val {
            font-size: 10.5pt; font-weight: bold;
            color: #1a1a1a; line-height: 1.45;
        }
        .blk-dark .val { color: #ffffff; }

        .sub {
            font-size: 8.5pt; font-weight: normal;
            color: #888888; display: block; margin-top: 2px;
        }
        .blk-dark .sub { color: #aaaaaa; }

        .amount      { font-size: 20pt; font-weight: bold; color: #ffffff; line-height: 1.1; }
        .amount-unit { font-size: 10pt; font-weight: normal; color: #aaaaaa; margin-left: 3px; }

        .bdg-ok {
            display: inline-block; padding: 2px 8px;
            font-size: 8.5pt; font-weight: bold;
            color: #15803d; background-color: #dcfce7; border: 1px solid #86efac;
        }
        .bdg-wait {
            display: inline-block; padding: 2px 8px;
            font-size: 8.5pt; font-weight: bold;
            color: #b45309; background-color: #fef9c3; border: 1px solid #fde047;
        }

        /* ════════════════════════
           SIGNATURES
        ════════════════════════ */
        .sig {
            width: 46.5%; height: 80px;
            padding: 10px 12px;
            border: 1px dashed #cccccc;
            vertical-align: top;
        }
        .sig-lbl {
            font-size: 7.5pt; font-weight: bold;
            letter-spacing: 0.1em; text-transform: uppercase; color: #888888;
        }
        .sig-line { border-top: 1px solid #cccccc; margin-top: 50px; }

        /* ════════════════════════
           PIED
        ════════════════════════ */
        .footer-wrap {
            margin-top: 6px;
            border-top: 1px solid #dddddd;
            background-color: #f9fbfd;
            padding: 10px 22px;
        }
        .footer-tbl { width: 100%; border-collapse: separate; border-spacing: 0; }
        .f-left  { font-size: 8pt; color: #888888; line-height: 1.6; vertical-align: middle; }
        .f-left strong { color: #1a1a1a; }
        .f-right { font-size: 8pt; color: #888888; text-align: right; vertical-align: middle; white-space: nowrap; width: 160px; }

        .body-pad { padding: 12px 22px; }

        /* Ligne vide entre 2 rangées de blocs */
        .gap-row { height: 6px; }
    </style>
</head>
<body>

{{-- ══════════ EN-TETE ══════════ --}}
<table class="hd-table" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="org-name">Tara Institut et Der Beste Betreuer</div>
            <div class="org-sub">Préinscription TELC — Document officiel</div>
            <div class="org-meta">
                Centre : {{ optional($registration->center)->name ?? '—' }}<br>
                Émis le : {{ optional($registration->registered_at)->format('d/m/Y à H:i') ?? now()->format('d/m/Y') }}
            </div>
        </td>
        <td class="hd-right">
            <div class="doc-lbl">Document officiel</div>
            <div class="doc-title">Reçu d'inscription</div>
            <div class="doc-num">N° {{ $registration->receipt_number }}</div>
            @if ($registration->payment_status === 'paid')
                <span class="pill-paid">Payé</span>
            @else
                <span class="pill-wait">En attente</span>
            @endif
        </td>
    </tr>
</table>

{{-- ══════════ CORPS ══════════ --}}
<div class="body-pad">

    {{-- Étudiant --}}
    <table class="sep" cellpadding="0" cellspacing="0">
        <tr><td class="sep-line"></td><td class="sep-txt">Étudiant</td><td class="sep-line"></td></tr>
    </table>
    <table class="grid" cellpadding="0" cellspacing="0">
        <tr>
            <td class="blk">
                <div class="lbl">Nom complet</div>
                <div class="val">{{ optional($registration->student)->full_name ?? '—' }}</div>
                <span class="sub">{{ optional($registration->student)->email ?? '—' }}</span>
            </td>
            <td class="gap-col"></td>
            <td class="blk">
                <div class="lbl">Date de naissance</div>
                <div class="val">{{ optional(optional($registration->student)->date_of_birth)->format('d/m/Y') ?? '—' }}</div>
                <span class="sub">{{ optional($registration->student)->place_of_birth ?? '' }}</span>
            </td>
        </tr>
        <tr><td colspan="3" class="gap-row"></td></tr>
        <tr>
            <td class="blk">
                <div class="lbl">Nationalité / Ville</div>
                <div class="val">{{ optional($registration->student)->country ?? '—' }}</div>
                <span class="sub">{{ optional($registration->student)->city ?? '' }}</span>
            </td>
            <td class="gap-col"></td>
            <td class="blk">
                <div class="lbl">Langue — Sexe</div>
                <div class="val">{{ \App\Models\Student::languageLabel(optional($registration->student)->base_language) ?? '—' }}</div>
                <span class="sub">{{ \App\Models\Student::genderLabel(optional($registration->student)->gender) ?? '—' }}</span>
            </td>
        </tr>
    </table>

    {{-- Session --}}
    <table class="sep" cellpadding="0" cellspacing="0">
        <tr><td class="sep-line"></td><td class="sep-txt">Session d'examen</td><td class="sep-line"></td></tr>
    </table>
    <table class="grid" cellpadding="0" cellspacing="0">
        <tr>
            <td class="blk">
                <div class="lbl">Titre de la session</div>
                <div class="val">{{ optional($registration->examSession)->title ?? '—' }}</div>
                <span class="sub">Niveau : {{ optional($registration->examSession)->level ?? '—' }}</span>
            </td>
            <td class="gap-col"></td>
            <td class="blk">
                <div class="lbl">Ville — Date d'examen</div>
                <div class="val">{{ optional($registration->examSession)->city ?? '—' }}</div>
                <span class="sub">{{ optional(optional($registration->examSession)->exam_date)->format('d/m/Y') ?? '' }}</span>
            </td>
        </tr>
    </table>

    {{-- Paiement --}}
    <table class="sep" cellpadding="0" cellspacing="0">
        <tr><td class="sep-line"></td><td class="sep-txt">Paiement</td><td class="sep-line"></td></tr>
    </table>
    <table class="grid" cellpadding="0" cellspacing="0">
        <tr>
            <td class="blk-dark">
                <div class="lbl">Montant dû</div>
                <div class="val">
                    <span class="amount">{{ number_format($registration->amount, 0, ',', ' ') }}</span><span class="amount-unit">FCFA</span>
                </div>
            </td>
            <td class="gap-col"></td>
            <td class="blk">
                <div class="lbl">Statut du paiement</div>
                <div class="val" style="margin-top:2px;">
                    @if ($registration->payment_status === 'paid')
                        <span class="bdg-ok">Payé</span>
                    @else
                        <span class="bdg-wait">En attente</span>
                    @endif
                    <span class="sub" style="margin-top:5px;">
                        Inscription :
                        @if ($registration->status === 'active')
                            <strong style="color:#15803d;">Active</strong>
                        @else
                            <strong style="color:#991b1b;">Annulée</strong>
                        @endif
                    </span>
                </div>
            </td>
        </tr>
        <tr><td colspan="3" class="gap-row"></td></tr>
        <tr>
            <td class="blk">
                <div class="lbl">Enregistré par</div>
                <div class="val">{{ optional($registration->registrar)->name ?? 'Système' }}</div>
            </td>
            <td class="gap-col"></td>
            <td class="blk">
                <div class="lbl">Date d'enregistrement</div>
                <div class="val">{{ optional($registration->registered_at)->format('d/m/Y') ?? '—' }}</div>
                <span class="sub">{{ optional($registration->registered_at)->format('H:i') }}</span>
            </td>
        </tr>
    </table>

    {{-- Validation --}}
    <table class="sep" cellpadding="0" cellspacing="0">
        <tr><td class="sep-line"></td><td class="sep-txt">Validation</td><td class="sep-line"></td></tr>
    </table>
    <table class="grid" cellpadding="0" cellspacing="0">
        <tr>
            <td class="sig">
                <div class="sig-lbl">Cachet du centre</div>
                <div class="sig-line"></div>
            </td>
            <td class="gap-col"></td>
            <td class="sig">
                <div class="sig-lbl">Signature du chef de centre</div>
                <div class="sig-line"></div>
            </td>
        </tr>
    </table>

</div>

{{-- ══════════ PIED ══════════ --}}
<div class="footer-wrap">
    <table class="footer-tbl" cellpadding="0" cellspacing="0">
        <tr>
            <td class="f-left">
                <strong>Tara Institut et Der Beste Betreuer</strong> — Préinscription TELC<br>
                Ce document est généré automatiquement et fait foi de l'inscription.
            </td>
            <td class="f-right">Réf. {{ $registration->receipt_number }}</td>
        </tr>
    </table>
</div>

</body>
</html>