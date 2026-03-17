@extends('layouts.app')

@section('page_title', 'Tableau de bord')
@section('page_kicker', 'Synth&egrave;se')
@section('page_subtitle')
    Vue d&apos;ensemble des inscriptions, des sessions et des paiements r&eacute;cents.
@endsection

@section('page_actions')
    <a href="{{ route('registrations.export', request()->only('center_id', 'level', 'date_from', 'date_to')) }}" class="btn btn-secondary">Exporter</a>
    @if ($mode === 'super_admin')
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Utilisateurs</a>
    @endif
    <a href="{{ route('registrations.create', request()->only('center_id')) }}" class="btn btn-primary">Nouvelle inscription</a>
@endsection

@push('styles')
<style>
.dash-shell{display:grid;gap:18px}
.dash-filter,.dash-panel,.dash-card{background:#fff;border:1px solid #e3e8f2;border-radius:18px;box-shadow:0 12px 28px rgba(15,23,42,.05)}
.dash-filter{padding:16px 18px}
.dash-filter form{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.dash-filter select,.dash-filter input{height:38px;padding:0 12px;border:1px solid #d8e1ef;border-radius:10px;background:#f8fbff;color:#132145}
.dash-actions{display:flex;gap:8px;flex-wrap:wrap;margin-left:auto}
.dash-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
.dash-card{padding:18px}
.dash-card__label{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#8a94ac}
.dash-card__value{font-size:1.9rem;line-height:1;font-weight:800;color:#102a56;margin-top:8px}
.dash-card__sub{font-size:12px;color:#6d7b96;margin-top:8px}
.dash-panel__head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;padding:18px 20px;border-bottom:1px solid #eef2f7}
.dash-panel__title{font-size:15px;font-weight:800;color:#102a56}
.dash-panel__text{font-size:12px;color:#72809f;margin-top:4px}
.dash-panel__body{padding:18px 20px}
.dash-badges{display:flex;gap:8px;flex-wrap:wrap}
.dash-badge{display:inline-flex;align-items:center;padding:5px 10px;border-radius:999px;font-size:11px;font-weight:700}
.dash-badge--open{background:#dcfce7;color:#15803d}
.dash-badge--closed{background:#e2e8f0;color:#475569}
.dash-badge--draft{background:#fef3c7;color:#b45309}
.dash-columns{display:grid;grid-template-columns:1.15fr .85fr;gap:18px}
.dash-list{display:grid;gap:12px}
.dash-item{display:block;padding:14px 15px;border:1px solid #e9eef6;border-radius:14px;background:#fbfdff;color:inherit;text-decoration:none}
.dash-item:hover{border-color:#ced9eb;background:#fff}
.dash-item__top{display:flex;justify-content:space-between;gap:12px;align-items:flex-start}
.dash-item__title{font-size:14px;font-weight:700;color:#102a56}
.dash-item__meta{font-size:12px;color:#6d7b96;margin-top:5px;line-height:1.5}
.dash-item__foot{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-top:12px;font-size:12px}
.dash-item__amount{font-weight:700;color:#102a56}
.dash-empty{padding:28px;border:1px dashed #d9e3f2;border-radius:14px;background:#fbfdff;text-align:center;color:#7b879e}
@media (max-width:1000px){.dash-grid,.dash-columns{grid-template-columns:1fr 1fr}}
@media (max-width:720px){.dash-grid,.dash-columns{grid-template-columns:1fr}.dash-actions{margin-left:0;width:100%}.dash-actions .btn{flex:1;justify-content:center}}
</style>
@endpush

@section('content')
@php
    $recordedAmount = (float) ($stats['recorded_amount'] ?? 0);
    $collectedAmount = (float) ($stats['collected_amount'] ?? 0);
    $depositedAmount = (float) ($stats['deposited_amount'] ?? 0);
    $paidCount = (int) ($stats['paid_count'] ?? 0);
@endphp

<div class="dash-shell">
    <section class="dash-filter">
        <form method="GET" action="{{ route('dashboard') }}">
            @if ($centerOptions->isNotEmpty())
                <select name="center_id">
                    <option value="">Tous les centres</option>
                    @foreach ($centerOptions as $center)
                        <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>
                            {{ $center->name }}
                        </option>
                    @endforeach
                </select>
            @endif
            <select name="level">
                <option value="">Tous les niveaux</option>
                @foreach ($levelOptions as $level)
                    <option value="{{ $level }}" {{ request('level') === $level ? 'selected' : '' }}>{{ $level }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
            <input type="date" name="date_to" value="{{ request('date_to') }}">
            <div class="dash-actions">
                <button type="submit" class="btn btn-secondary">Appliquer</button>
                @if (request()->hasAny(['center_id', 'level', 'date_from', 'date_to']))
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost">Effacer</a>
                @endif
            </div>
        </form>
    </section>

    <section class="dash-grid">
        <article class="dash-card">
            <div class="dash-card__label">Inscriptions</div>
            <div class="dash-card__value">{{ $stats['registrations'] ?? 0 }}</div>
            <div class="dash-card__sub">Dossiers actifs dans le p&eacute;rim&egrave;tre courant.</div>
        </article>
        <article class="dash-card">
            <div class="dash-card__label">Paiements</div>
            <div class="dash-card__value">{{ $paidCount }}</div>
            <div class="dash-card__sub">Inscriptions pay&eacute;es.</div>
        </article>
        <article class="dash-card">
            <div class="dash-card__label">Sessions</div>
            <div class="dash-card__value">{{ $stats['sessions'] ?? 0 }}</div>
            <div class="dash-card__sub">Sessions visibles apr&egrave;s filtres.</div>
        </article>
        <article class="dash-card">
            <div class="dash-card__label">{{ $mode === 'super_admin' ? 'Centres' : 'Etudiants' }}</div>
            <div class="dash-card__value">{{ $mode === 'super_admin' ? ($stats['centers'] ?? 0) : ($stats['students'] ?? 0) }}</div>
            <div class="dash-card__sub">{{ $mode === 'super_admin' ? 'Centres actifs.' : 'Candidats suivis.' }}</div>
        </article>
    </section>

    <section class="dash-grid">
        <article class="dash-card">
            <div class="dash-card__label">Montant enregistr&eacute;</div>
            <div class="dash-card__value">{{ number_format($recordedAmount, 0, ',', ' ') }}</div>
            <div class="dash-card__sub">FCFA saisis sur les dossiers actifs.</div>
        </article>
        <article class="dash-card">
            <div class="dash-card__label">Montant collect&eacute;</div>
            <div class="dash-card__value">{{ number_format($collectedAmount, 0, ',', ' ') }}</div>
            <div class="dash-card__sub">FCFA d&eacute;j&agrave; pay&eacute;s.</div>
        </article>
        <article class="dash-card">
            <div class="dash-card__label">Montant d&eacute;pos&eacute;</div>
            <div class="dash-card__value">{{ number_format($depositedAmount, 0, ',', ' ') }}</div>
            <div class="dash-card__sub">FCFA valid&eacute;s en banque.</div>
        </article>
        <article class="dash-card">
            <div class="dash-card__label">Statut des sessions</div>
            <div class="dash-badges" style="margin-top:10px">
                <span class="dash-badge dash-badge--open">{{ $sessionStatusSummary['open'] ?? 0 }} ouverte{{ ($sessionStatusSummary['open'] ?? 0) > 1 ? 's' : '' }}</span>
                <span class="dash-badge dash-badge--closed">{{ $sessionStatusSummary['closed'] ?? 0 }} ferm&eacute;e{{ ($sessionStatusSummary['closed'] ?? 0) > 1 ? 's' : '' }}</span>
                <span class="dash-badge dash-badge--draft">{{ $sessionStatusSummary['draft'] ?? 0 }} brouillon{{ ($sessionStatusSummary['draft'] ?? 0) > 1 ? 's' : '' }}</span>
            </div>
        </article>
    </section>

    <section class="dash-columns">
        <article class="dash-panel">
            <div class="dash-panel__head">
                <div>
                    <div class="dash-panel__title">Sessions r&eacute;centes</div>
                    <div class="dash-panel__text">Suivi rapide des derni&egrave;res sessions visibles.</div>
                </div>
                <a href="{{ route('sessions.index', request()->only('center_id')) }}" class="btn btn-ghost btn-sm">Voir tout</a>
            </div>
            <div class="dash-panel__body">
                <div class="dash-list">
                    @forelse ($recentSessions as $session)
                        @php
                            $statusClass = $session->status === 'open' ? 'open' : ($session->status === 'closed' ? 'closed' : 'draft');
                            $centerLabel = optional($session->center)->name ?: 'Centre non d&eacute;fini';
                        @endphp
                        <a href="{{ route('sessions.show', $session) }}" class="dash-item">
                            <div class="dash-item__top">
                                <div>
                                    <div class="dash-item__title">{{ $session->title }}</div>
                                    <div class="dash-item__meta">{{ $centerLabel }} · Niveau {{ $session->level ?: '-' }}</div>
                                </div>
                                <span class="dash-badge dash-badge--{{ $statusClass }}">
                                    {{ $session->status === 'open' ? 'Ouverte' : ($session->status === 'closed' ? 'Fermee' : 'Brouillon') }}
                                </span>
                            </div>
                            <div class="dash-item__foot">
                                <span>{{ $session->active_registrations_count ?? 0 }} inscrit(s)</span>
                                <span>{{ optional($session->exam_date)->format('d/m/Y') ?: 'Date non definie' }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="dash-empty">Aucune session &agrave; afficher pour le moment.</div>
                    @endforelse
                </div>
            </div>
        </article>

        <article class="dash-panel">
            <div class="dash-panel__head">
                <div>
                    <div class="dash-panel__title">Derni&egrave;res inscriptions</div>
                    <div class="dash-panel__text">Activit&eacute; r&eacute;cente et statut des paiements.</div>
                </div>
                <a href="{{ route('registrations.index', request()->only('center_id', 'level')) }}" class="btn btn-ghost btn-sm">Voir tout</a>
            </div>
            <div class="dash-panel__body">
                <div class="dash-list">
                    @forelse ($recentRegistrations as $registration)
                        @if ($registration->payment_status === 'paid')
                        <a href="{{ route('registrations.receipt', $registration) }}" class="dash-item">
                            <div class="dash-item__top">
                                <div>
                                    <div class="dash-item__title">{{ optional($registration->student)->full_name ?: 'Candidat inconnu' }}</div>
                                    <div class="dash-item__meta">{{ optional($registration->examSession)->title ?: 'Session non definie' }}</div>
                                </div>
                                <span class="dash-badge dash-badge--{{ $registration->payment_status === 'paid' ? 'open' : 'draft' }}">
                                    {{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}
                                </span>
                            </div>
                            <div class="dash-item__foot">
                                <span class="dash-item__amount">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                <span>{{ optional($registration->registered_at)->format('d/m/Y H:i') ?: '-' }}</span>
                            </div>
                        </a>
                    @else
                        <div class="dash-item" style="opacity:0.6; cursor:not-allowed;">
                            <div class="dash-item__top">
                                <div>
                                    <div class="dash-item__title">{{ optional($registration->student)->full_name ?: 'Candidat inconnu' }}</div>
                                    <div class="dash-item__meta">{{ optional($registration->examSession)->title ?: 'Session non definie' }}</div>
                                </div>
                                <span class="dash-badge dash-badge--{{ $registration->payment_status === 'paid' ? 'open' : 'draft' }}">
                                    {{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}
                                </span>
                            </div>
                            <div class="dash-item__foot">
                                <span class="dash-item__amount">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                <span>{{ optional($registration->registered_at)->format('d/m/Y H:i') ?: '-' }}</span>
                            </div>
                        </div>
                    @endif
                    @empty
                        <div class="dash-empty">Aucune inscription r&eacute;cente.</div>
                    @endforelse
                </div>
            </div>
        </article>
    </section>
</div>
@endsection

