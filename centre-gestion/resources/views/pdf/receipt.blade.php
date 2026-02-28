<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 10px; }
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; font-size: 11px; margin: 20px; }
        .header { margin-bottom: 30px; }
        .logo { width: 120px; float: left; }
        .header-text { float: right; text-align: right; margin-top: 10px; }
        .title { font-size: 16px; font-weight: bold; color: #1e40af; clear: both; text-align: center; padding-top: 20px; text-transform: uppercase; letter-spacing: 2px; }
        
        .section-info { margin-top: 30px; width: 100%; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; }
        .info-col { width: 50%; vertical-align: top; }
        
        .table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .table th { background: #1e40af; color: white; padding: 10px; text-align: left; text-transform: uppercase; font-size: 9px; }
        .table td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; }
        
        .summary { margin-top: 20px; width: 100%; }
        .total-box { background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .total-row { font-size: 13px; font-weight: bold; color: #1e40af; }
        .balance-row { font-size: 11px; color: #dc2626; margin-top: 5px; }
        
        .footer { position: fixed; bottom: 30px; width: 100%; text-align: center; color: #94a3b8; font-size: 8px; }
        .signature { margin-top: 40px; }
        .signature-box { float: right; width: 150px; text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 5px; font-weight: bold; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logo }}" class="logo">
        <div class="header-text">
            <strong style="font-size: 14px;">{{ $centre->name }}</strong><br>
            {{ $centre->city }}<br>
            Date: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="title">Reçu de Paiement</div>

    <div class="section-info">
        <table style="width: 100%;">
            <tr>
                <td class="info-col">
                    <span style="color: #64748b; text-transform: uppercase; font-size: 9px; font-weight: bold;">Étudiant</span><br>
                    <strong style="font-size: 12px;">{{ $student->first_name }} {{ $student->last_name }}</strong><br>
                    Tél: {{ $student->phone }}
                </td>
                <td class="info-col" style="text-align: right;">
                    <span style="color: #64748b; text-transform: uppercase; font-size: 9px; font-weight: bold;">Référence Reçu</span><br>
                    <strong style="font-size: 12px;">{{ $payment->reference }}</strong><br>
                    Mode: {{ $payment->mode }}
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 10px;">
        <span style="font-size: 10px; font-weight: bold;">Programme :</span> {{ $fee->label }} ({{ $fee->language }})
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Désignation des frais</th>
                <th style="text-align: right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @if($payment->amount_registration > 0)
            <tr>
                <td>Frais d'Inscription Administrative</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($payment->amount_registration, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endif
            @if($payment->amount_tuition > 0)
            <tr>
                <td>Versement Scolarité / Pension</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($payment->amount_tuition, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="summary">
        <div class="total-box">
            <table style="width: 100%;">
                <tr class="total-row">
                    <td>MONTANT TOTAL VERSÉ</td>
                    <td style="text-align: right;">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr class="balance-row">
                    <td>RESTE À SOLDER (Cycle actuel)</td>
                    <td style="text-align: right;">{{ number_format($balance, 0, ',', ' ') }} FCFA</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="signature">
        <div class="signature-box">
            Le Responsable Financier<br>
            (Cachet et Signature)
        </div>
    </div>

    <div class="footer">
        TARA FORMATION - Système de Gestion Intégré<br>
        Ce reçu est généré numériquement et constitue une preuve de paiement officielle.
    </div>
</body>
</html>