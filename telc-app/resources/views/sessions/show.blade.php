@extends('layouts.app')

@section('page_title', $session->title)
@section('page_kicker', 'Sessions')
@section('page_subtitle')
    Consultez les inscrits, les montants et le suivi complet de cette session.
@endsection

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('sessions.index') }}">Retour aux sessions</a>
    @if ($user->isSuperAdmin() && \App\Models\ExamSession::supportsArchiving())
        <form method="POST" action="{{ route('sessions.archive', $session) }}" style="display:inline-block" data-loading="true" data-loading-text="Archivage de la session en cours...">
            @csrf
            <input type="hidden" name="action" value="{{ $session->archived_at ? 'restore' : 'archive' }}">
            <button type="submit" class="btn btn-ghost">{{ $session->archived_at ? 'Restaurer' : 'Archiver' }}</button>
        </form>
    @endif
@endsection

@push('styles')
<style>
.sd-shell{display:flex;flex-direction:column;gap:18px}
.sd-summary{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.sd-card,.sd-panel{background:#fff;border:1px solid #e7ebf3;border-radius:16px;box-shadow:0 10px 24px rgba(15,23,42,.05)}
.sd-card{padding:18px 20px;display:flex;flex-direction:column;gap:6px}
.sd-label{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#8b95aa}
.sd-value{font-size:1.75rem;line-height:1;font-weight:800;color:#0f172a}
.sd-sub{font-size:12px;color:#64748b}
.sd-grid{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,.9fr);gap:18px}
.sd-panel{padding:20px}
.sd-panel__head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px}
.sd-panel__title{font-size:16px;font-weight:800;color:#0f172a}
.sd-panel__text{font-size:12.5px;color:#64748b;margin-top:4px}
.sd-meta{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.sd-meta__item{padding:14px;border-radius:12px;background:#f8fafc;border:1px solid #e8edf5}
.sd-meta__label{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:6px}
.sd-meta__value{font-size:13px;color:#0f172a;font-weight:700}
.sd-notes{margin-top:14px;padding:15px 16px;border-radius:12px;background:#f8fafc;border:1px solid #e8edf5;font-size:13px;color:#475569;line-height:1.6}
.sd-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:999px;font-size:11px;font-weight:700}
.sd-badge--open{background:#dcfce7;color:#15803d}
.sd-badge--draft{background:#fef3c7;color:#b45309}
.sd-badge--closed{background:#e2e8f0;color:#475569}
.sd-table{width:100%;border-collapse:collapse}
.sd-table th,.sd-table td{padding:12px 10px;border-bottom:1px solid #eef2f7;text-align:left;font-size:13px;vertical-align:top}
.sd-table th{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8}
.sd-muted{font-size:12px;color:#64748b}
.sd-amount{font-weight:700;color:#0f172a;white-space:nowrap}
.sd-status{display:inline-flex;align-items:center;padding:4px 9px;border-radius:999px;font-size:11px;font-weight:700}
.sd-status--paid{background:#dcfce7;color:#15803d}
.sd-status--pending{background:#fef3c7;color:#b45309}
.sd-status--cancelled{background:#fee2e2;color:#b91c1c}
.sd-empty{padding:26px;border-radius:12px;border:1px dashed #dbe3ef;background:#f8fafc;text-align:center;color:#64748b}
@media(max-width:980px){.sd-summary,.sd-grid,.sd-meta{grid-template-columns:1fr 1fr}}
@media(max-width:720px){.sd-summary,.sd-grid,.sd-meta{grid-template-columns:1fr}.sd-table{display:block;overflow-x:auto;white-space:nowrap}}
</style>
@endpush

@section('content')
@php
    $centerNames = $session->centers->pluck('name')->filter()->values();
    if ($centerNames->isEmpty() && optional($session->center)->name) {
        $centerNames = collect([optional($session->center)->name]);
    }
    $centerLabel = $centerNames->isNotEmpty() ? $centerNames->implode(', ') : 'Centre non rattaché';
    $statusClass = $session->status === 'open' ? 'open' : ($session->status === 'closed' ? 'closed' : 'draft');
@endphp

<div class="sd-shell">
    <section class="sd-summary">
        <article class="sd-card">
            <div class="sd-label">Inscrits</div>
            <div class="sd-value">{{ $summary['total'] }}</div>
            <div class="sd-sub">{{ $summary['active'] }} actif(s)</div>
        </article>
        <article class="sd-card">
            <div class="sd-label">Paiements validés</div>
            <div class="sd-value">{{ $summary['paid'] }}</div>
            <div class="sd-sub">{{ number_format($summary['paid_amount'], 0, ',', ' ') }} FCFA payés</div>
        </article>
        <article class="sd-card">
            <div class="sd-label">En attente</div>
            <div class="sd-value">{{ $summary['pending'] }}</div>
            <div class="sd-sub">{{ number_format($summary['pending_amount'], 0, ',', ' ') }} FCFA à encaisser</div>
        </article>
        <article class="sd-card">
            <div class="sd-label">Annulées</div>
            <div class="sd-value">{{ $summary['cancelled'] }}</div>
            <div class="sd-sub">{{ number_format($summary['recorded_amount'], 0, ',', ' ') }} FCFA enregistrés</div>
        </article>
    </section>

    <section class="sd-grid">
        <article class="sd-panel">
            <div class="sd-panel__head">
                <div>
                    <div class="sd-panel__title">Informations de la session</div>
                    <div class="sd-panel__text">Les données restent consultables même lorsque la session est fermée.</div>
                </div>
                <span class="sd-badge sd-badge--{{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            <div class="sd-meta">
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Centre</div>
                    <div class="sd-meta__value">{{ $centerLabel }}</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Ville</div>
                    <div class="sd-meta__value">{{ $session->city ?: 'Non renseignée' }}</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Niveau</div>
                    <div class="sd-meta__value">{{ $session->level }}</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Tarif</div>
                    <div class="sd-meta__value">{{ number_format($session->price, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Examen</div>
                    <div class="sd-meta__value">{{ optional($session->exam_date)->format('d/m/Y') ?: 'Non définie' }}</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Clôture des inscriptions</div>
                    <div class="sd-meta__value">{{ optional($session->closes_at)->format('d/m/Y') ?: 'Non définie' }}</div>
                </div>
            </div>

            @if ($session->notes)
                <div class="sd-notes">{{ $session->notes }}</div>
            @endif
        </article>

        <article class="sd-panel">
            <div class="sd-panel__head">
                <div>
                    <div class="sd-panel__title">Résumé financier</div>
                    <div class="sd-panel__text">Suivi des montants enregistrés sur la session.</div>
                </div>
            </div>

            <div class="sd-meta">
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Montant enregistré</div>
                    <div class="sd-meta__value">{{ number_format($summary['recorded_amount'], 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Montant payé</div>
                    <div class="sd-meta__value">{{ number_format($summary['paid_amount'], 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Reste à encaisser</div>
                    <div class="sd-meta__value">{{ number_format($summary['pending_amount'], 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="sd-meta__item">
                    <div class="sd-meta__label">Période d'inscription</div>
                    <div class="sd-meta__value">{{ optional($session->opens_at)->format('d/m/Y') ?: '-' }} au {{ optional($session->closes_at)->format('d/m/Y') ?: 'Non définie' }}</div>
                </div>
            </div>
        </article>
    </section>

    <section class="sd-panel">
        <div class="sd-panel__head">
            <div>
                <div class="sd-panel__title">Liste des inscrits</div>
                <div class="sd-panel__text">Consultez les personnes enregistrées, leur paiement et l'utilisateur ayant saisi le dossier.</div>
            </div>
        </div>

        @if ($registrations->isEmpty())
            <div class="sd-empty">Aucune inscription n'est encore liée à cette session.</div>
        @else
            <div style="overflow-x:auto;">
                <table class="sd-table">
                    <thead>
                        <tr>
                            <th>Candidat</th>
                            <th>Centre</th>
                            <th>Montant</th>
                            <th>Paiement</th>
                            <th>Inscrit le</th>
                            <th>Saisi par</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            @php
                                $paymentClass = $registration->status === 'cancelled'
                                    ? 'cancelled'
                                    : ($registration->payment_status === 'paid' ? 'paid' : 'pending');
                                $paymentLabel = $registration->status === 'cancelled'
                                    ? 'Annulée'
                                    : ($registration->payment_status === 'paid' ? 'Payé' : 'En attente');
                            @endphp
                            <tr>
                                <td>
                                    <div>{{ optional($registration->student)->full_name ?: 'Candidat inconnu' }}</div>
                                    <div class="sd-muted">{{ optional($registration->student)->email ?: 'E-mail non renseigné' }}</div>
                                </td>
                                <td>{{ optional($registration->center)->name ?: 'Centre non rattaché' }}</td>
                                <td class="sd-amount">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</td>
                                <td><span class="sd-status sd-status--{{ $paymentClass }}">{{ $paymentLabel }}</span></td>
                                <td>{{ optional($registration->registered_at)->format('d/m/Y H:i') ?: 'Non définie' }}</td>
                                <td>
                                    <div class="user-line">
                                        @include('partials.user-avatar', ['user' => $registration->registrar, 'size' => 24])
                                        <span>{{ optional($registration->registrar)->name ?: 'Non renseigne' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection








