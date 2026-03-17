@extends('layouts.app')

@section('page_title', 'Tableau de bord')
@section('page_kicker', 'Synthese')
@section('page_subtitle', "Vue d'ensemble des indicateurs et des activites recentes.")

@section('page_actions')
    @if ($mode === 'super_admin')
        <a href="{{ route('registrations.export') }}" class="btn btn-secondary">Rapports et export</a>
        <a href="{{ route('users.index') }}" class="btn btn-primary">Utilisateurs</a>
    @else
        <a href="{{ route('registrations.export', request()->only('center_id', 'level', 'date_from', 'date_to')) }}" class="btn btn-secondary">Exporter</a>
        <a href="{{ route('deposits.create', request()->only('center_id')) }}" class="btn btn-secondary">Nouveau depot</a>
        <a href="{{ route('registrations.create', request()->only('center_id')) }}" class="btn btn-primary">Nouvelle inscription</a>
    @endif
@endsection

@if ($mode !== 'super_admin')
    @section('page_header_actions')
        <span class="badge success">{{ $sessionStatusSummary['open'] ?? 0 }} ouverte{{ ($sessionStatusSummary['open'] ?? 0) > 1 ? 's' : '' }}</span>
        <span class="badge neutral">{{ $sessionStatusSummary['closed'] ?? 0 }} fermee{{ ($sessionStatusSummary['closed'] ?? 0) > 1 ? 's' : '' }}</span>
        <span class="badge warning">{{ $sessionStatusSummary['draft'] ?? 0 }} brouillon{{ ($sessionStatusSummary['draft'] ?? 0) > 1 ? 's' : '' }}</span>
    @endsection
@endif

@push('styles')
<style>
.db-shell {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-height: calc(100vh - 200px);
    overflow: hidden;
}

.db-filter {
    padding: 12px 16px;
    border: 1px solid #e5eaf4;
    border-radius: 12px;
    background: linear-gradient(180deg, #fff, #fbfcff);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
}

.db-inline-badges {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.db-filter form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
}

.db-filter__label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding-right: 12px;
    border-right: 1px solid #e7ebf3;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #7b849e;
    white-space: nowrap;
}

.db-filter select,
.db-filter input {
    min-width: 0;
    flex: 0 1 140px;
    height: 32px;
    padding: 0 10px;
    border: 1px solid #d8e1ef;
    border-radius: 8px;
    background: #f7f9fd;
    color: #132145;
    font-size: 12px;
}

.db-filter__dates {
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 0 1 240px;
    min-width: 200px;
}

.db-filter__dates input {
    flex: 1;
}

.db-filter__actions {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}

.db-kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.db-kpis--five {
    grid-template-columns: 1.3fr repeat(4, 1fr);
}

.db-card {
    background: linear-gradient(180deg, #fff, #fbfcff);
    border: 1px solid #e7ebf3;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    transition: transform 0.2s ease;
}

.db-card:hover {
    transform: translateY(-2px);
}

.db-kpi {
    padding: 16px;
    display: grid;
    gap: 4px;
}

.db-kpi--primary {
    background: linear-gradient(145deg, #fff, #eef4ff);
}

.db-kpi__label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #8a94ac;
}

.db-kpi__value {
    font-size: 1.5rem;
    line-height: 1;
    font-weight: 800;
    color: #102a56;
}

.db-kpi__sub {
    font-size: 11px;
    color: #7b849e;
}

.db-fin {
    padding: 16px;
    display: grid;
    gap: 4px;
}

.db-fin__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.db-fin__label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.db-fin__amount {
    font-size: 1.4rem;
    font-weight: 800;
    color: #111827;
    line-height: 1;
}

.db-fin__bar {
    height: 4px;
    background: rgba(15, 23, 42, 0.08);
    border-radius: 999px;
    overflow: hidden;
}

.db-fin__fill {
    height: 100%;
    border-radius: 999px;
}

.db-fin--slate {
    background: linear-gradient(145deg, #f8fafc, #eef2f7);
}

.db-fin--green {
    background: linear-gradient(145deg, #f0fdf4, #dcfce7);
}

.db-fin--amber {
    background: linear-gradient(145deg, #fffbeb, #fef3c7);
}

.db-fin--green .db-fin__label {
    color: #15803d;
}

.db-fin--green .db-fin__fill {
    background: #22c55e;
}

.db-fin--amber .db-fin__label {
    color: #b45309;
}

.db-fin--amber .db-fin__fill {
    background: #f59e0b;
}

.db-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.db-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 16px;
}

.db-section__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 12px 16px;
    border-bottom: 1px solid #eef2f7;
}

.db-section__title {
    font-size: 13px;
    font-weight: 700;
    color: #102a56;
}

.db-section__sub {
    font-size: 11px;
    color: #72809f;
    margin-top: 2px;
}

.db-stack {
    display: grid;
    gap: 8px;
    padding: 12px;
    max-height: 300px;
    overflow-y: auto;
}

.db-session,
.db-registration {
    display: block;
    padding: 12px;
    border: 1px solid #e6ecf7;
    border-radius: 10px;
    background: #fff;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
}

.db-session:hover,
.db-registration:hover {
    border-color: #d8e1ef;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
}

.db-session__top,
.db-registration__top,
.db-registration__foot {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
}

.db-session__title,
.db-registration__title {
    font-size: 12px;
    font-weight: 700;
    color: #102a56;
    line-height: 1.4;
}

.db-session__meta,
.db-registration__meta {
    margin-top: 4px;
    font-size: 11px;
    line-height: 1.4;
    color: #6d7b96;
}

.db-session__stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin-top: 8px;
}

.db-mini {
    padding: 8px;
    border-radius: 8px;
    background: #f8fbff;
    border: 1px solid #e4ebf6;
}

.db-mini__label {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #8a94ac;
}

.db-mini__value {
    margin-top: 2px;
    font-size: 12px;
    font-weight: 700;
    color: #102a56;
}

.db-empty {
    padding: 16px;
    border: 1px dashed #d8e3f4;
    border-radius: 10px;
    background: #fbfcff;
    text-align: center;
    color: #72809f;
    font-size: 11px;
}

.db-empty strong {
    display: block;
    margin-bottom: 4px;
    color: #102a56;
}

.db-compact-table {
    display: grid;
    gap: 8px;
    padding: 12px;
}

.db-compact-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border: 1px solid #e6ecf7;
    border-radius: 8px;
    background: #fff;
}

.db-compact-label {
    font-size: 10px;
    font-weight: 700;
    color: #8a94ac;
    min-width: 80px;
}

.db-compact-value {
    font-size: 11px;
    font-weight: 600;
    color: #102a56;
}

.db-compact-bar {
    flex: 1;
    height: 4px;
    background: #e7ebf3;
    border-radius: 2px;
    overflow: hidden;
}

.db-compact-fill {
    height: 100%;
    background: #22c55e;
    border-radius: 2px;
}

@media (max-width: 1100px) {
    .db-kpis,
    .db-kpis--five,
    .db-grid-2,
    .db-grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .db-kpi--primary {
        grid-column: span 2;
    }
    
    .db-filter form {
        flex-wrap: wrap;
    }
    
    .db-filter__actions {
        margin-left: 0;
        width: 100%;
    }
}

@media (max-width: 700px) {
    .db-kpis,
    .db-kpis--five,
    .db-grid-2,
    .db-grid-3,
    .db-session__stats {
        grid-template-columns: 1fr;
    }
    
    .db-kpi--primary {
        grid-column: auto;
    }
    
    .db-filter form,
    .db-filter__dates,
    .db-filter__actions {
        width: 100%;
    }
    
    .db-filter select,
    .db-filter input,
    .db-btn {
        width: 100%;
    }
    
    .db-session__top,
    .db-registration__top,
    .db-registration__foot,
    .db-section__head {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endpush

@section('content')
@php
    $recordedAmount = (float) ($stats['recorded_amount'] ?? 0);
    $collectedAmount = (float) ($stats['collected_amount'] ?? 0);
    $depositedAmount = (float) ($stats['deposited_amount'] ?? 0);
    $registrationsCount = (int) ($stats['registrations'] ?? 0);
    $collectPct = $recordedAmount > 0 ? min(100, round(($collectedAmount / $recordedAmount) * 100)) : 0;
    $depositPct = $collectedAmount > 0 ? min(100, round(($depositedAmount / $collectedAmount) * 100)) : 0;
    $statusLabels = [
        \App\Models\ExamSession::STATUS_OPEN => ['label' => 'Ouverte', 'class' => 'success'],
        \App\Models\ExamSession::STATUS_CLOSED => ['label' => 'Fermee', 'class' => 'neutral'],
        \App\Models\ExamSession::STATUS_DRAFT => ['label' => 'Brouillon', 'class' => 'warning'],
    ];
@endphp

<div class="db-shell">
    <div class="db-filter">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="db-filter__label">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                Filtres
            </div>
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
                <option value="">Tous niveaux</option>
                @foreach ($levelOptions as $level)
                    <option value="{{ $level }}" {{ request('level') === $level ? 'selected' : '' }}>
                        {{ $level }}
                    </option>
                @endforeach
            </select>
            <div class="db-filter__dates">
                <input type="date" name="date_from" value="{{ request('date_from') }}">
                <span>au</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="db-filter__actions">
                <button type="submit" class="btn btn-secondary db-btn">Appliquer</button>
                @if (request()->hasAny(['center_id', 'level', 'date_from', 'date_to']))
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost db-btn">Effacer</a>
                @endif
            </div>
        </form>
    </div>

    @if ($mode !== 'super_admin')
        <div class="db-inline-badges">
            <span class="badge success">{{ $sessionStatusSummary['open'] ?? 0 }} ouverte{{ ($sessionStatusSummary['open'] ?? 0) > 1 ? 's' : '' }}</span>
            <span class="badge neutral">{{ $sessionStatusSummary['closed'] ?? 0 }} fermee{{ ($sessionStatusSummary['closed'] ?? 0) > 1 ? 's' : '' }}</span>
            <span class="badge warning">{{ $sessionStatusSummary['draft'] ?? 0 }} brouillon{{ ($sessionStatusSummary['draft'] ?? 0) > 1 ? 's' : '' }}</span>
        </div>
    @endif

    @if ($mode === 'super_admin')
        <div class="db-kpis db-kpis--five">
            <div class="db-card db-kpi db-kpi--primary">
                <div class="db-kpi__label">Inscriptions actives</div>
                <div class="db-kpi__value">{{ $registrationsCount }}</div>
                <div class="db-kpi__sub">dossiers valides</div>
            </div>
            <div class="db-card db-kpi">
                <div class="db-kpi__label">Centres</div>
                <div class="db-kpi__value">{{ $stats['centers'] ?? 0 }}</div>
                <div class="db-kpi__sub">centres visibles</div>
            </div>
            <div class="db-card db-kpi">
                <div class="db-kpi__label">Sessions</div>
                <div class="db-kpi__value">{{ $stats['sessions'] ?? 0 }}</div>
                <div class="db-kpi__sub">sessions filtrees</div>
            </div>
            <div class="db-card db-kpi">
                <div class="db-kpi__label">Etudiants</div>
                <div class="db-kpi__value">{{ $stats['students'] ?? 0 }}</div>
                <div class="db-kpi__sub">dossiers actifs</div>
            </div>
            <div class="db-card db-kpi">
                <div class="db-kpi__label">Croissance</div>
                <div class="db-kpi__value">{{ $advanced['growth'] !== null ? $advanced['growth'].'%' : 'N/A' }}</div>
                <div class="db-kpi__sub">vs session precedente</div>
            </div>
        </div>

        <div class="db-kpis">
            <div class="db-card db-fin db-fin--slate">
                <div class="db-fin__top">
                    <span class="db-fin__label">Montant enregistre</span>
                </div>
                <div class="db-fin__amount">{{ number_format($recordedAmount, 0, ',', ' ') }}</div>
                <div class="db-kpi__sub">FCFA sur les inscriptions actives</div>
            </div>
            <div class="db-card db-fin db-fin--green">
                <div class="db-fin__top">
                    <span class="db-fin__label">Montant collecte</span>
                    <span>{{ $collectPct }}%</span>
                </div>
                <div class="db-fin__amount">{{ number_format($collectedAmount, 0, ',', ' ') }}</div>
                <div class="db-fin__bar">
                    <div class="db-fin__fill" style="width: {{ $collectPct }}%"></div>
                </div>
                <div class="db-kpi__sub">{{ $collectPct }}% du montant enregistre</div>
            </div>
            <div class="db-card db-fin db-fin--amber">
                <div class="db-fin__top">
                    <span class="db-fin__label">Montant depose</span>
                    <span>{{ $depositPct }}%</span>
                </div>
                <div class="db-fin__amount">{{ number_format($depositedAmount, 0, ',', ' ') }}</div>
                <div class="db-fin__bar">
                    <div class="db-fin__fill" style="width: {{ $depositPct }}%"></div>
                </div>
                <div class="db-kpi__sub">{{ $depositPct }}% du montant collecte</div>
            </div>
            <div class="db-card db-fin db-fin--slate">
                <div class="db-fin__top">
                    <span class="db-fin__label">Utilisateurs bloques</span>
                </div>
                <div class="db-fin__amount">{{ $advanced['blocked_users'] ?? 0 }}</div>
                <div class="db-kpi__sub">comptes a suivre</div>
            </div>
        </div>

        <div class="db-grid-3">
            <section class="db-card">
                <div class="db-section__head">
                    <div>
                        <div class="db-section__title">Inscriptions par centre</div>
                    </div>
                </div>
                <div class="db-compact-table">
                    @forelse ($advanced['by_center'] as $row)
                        @php $percent = $registrationsCount > 0 ? round(($row->total / $registrationsCount) * 100) : 0; @endphp
                        <div class="db-compact-row">
                            <span class="db-compact-label">{{ $row->name }}</span>
                            <span class="db-compact-value">{{ $row->total }}</span>
                            <div class="db-compact-bar">
                                <div class="db-compact-fill" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="db-compact-value">{{ $percent }}%</span>
                        </div>
                    @empty
                        <div class="db-empty">
                            <strong>Aucune donnee</strong>
                            Les inscriptions par centre apparaitront ici.
                        </div>
                    @endforelse
                </div>
            </section>
            
            <section class="db-card">
                <div class="db-section__head">
                    <div>
                        <div class="db-section__title">Inscriptions par session</div>
                    </div>
                </div>
                <div class="db-compact-table">
                    @forelse ($advanced['by_session'] as $row)
                        <div class="db-compact-row">
                            <span class="db-compact-label">{{ Str::limit($row->title, 25) }}</span>
                            <span class="db-compact-value">{{ $row->total }}</span>
                        </div>
                    @empty
                        <div class="db-empty">
                            <strong>Aucune session</strong>
                            Les sessions apparaitront ici.
                        </div>
                    @endforelse
                </div>
            </section>
            
            <section class="db-card">
                <div class="db-section__head">
                    <div>
                        <div class="db-section__title">Revenus mensuels</div>
                    </div>
                </div>
                <div class="db-compact-table">
                    @forelse ($advanced['monthly_revenue'] as $row)
                        <div class="db-compact-row">
                            <span class="db-compact-label">{{ str_replace('-', '/', $row->period) }}</span>
                            <span class="db-compact-value">{{ number_format((float) $row->total, 0, ',', ' ') }} FCFA</span>
                        </div>
                    @empty
                        <div class="db-empty">
                            <strong>Aucun revenu</strong>
                            Les revenus apparaitront ici.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="db-card">
            <div class="db-section__head">
                <div>
                    <div class="db-section__title">Dernieres inscriptions</div>
                </div>
                <a href="{{ route('registrations.index', request()->only('center_id', 'level')) }}" class="table-card-action">Voir tout</a>
            </div>
            <div class="db-stack">
                @forelse ($recentRegistrations as $registration)
                    @if ($registration->payment_status === 'paid')
                        <a href="{{ route('registrations.receipt', $registration) }}" class="db-registration">
                        <div class="db-registration__top">
                            <div>
                                <div class="db-registration__title">{{ optional($registration->student)->full_name ?: 'Candidat' }}</div>
                                <div class="db-registration__meta">{{ optional($registration->examSession)->title ?: 'Session non definie' }}</div>
                            </div>
                            <span class="badge {{ $registration->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}
                            </span>
                        </div>
                        <div class="db-registration__foot">
                            <span class="td-mono">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                            <span class="td-muted">{{ optional($registration->registered_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    </a>
                    @else
                        <div class="db-registration" style="opacity:0.6; cursor:not-allowed;">
                        <div class="db-registration__top">
                            <div>
                                <div class="db-registration__title">{{ optional($registration->student)->full_name ?: 'Candidat' }}</div>
                                <div class="db-registration__meta">{{ optional($registration->examSession)->title ?: 'Session non definie' }}</div>
                            </div>
                            <span class="badge {{ $registration->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}
                            </span>
                        </div>
                        <div class="db-registration__foot">
                            <span class="td-mono">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                            <span class="td-muted">{{ optional($registration->registered_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    @endif
                @empty
                    <div class="db-empty">
                        <strong>Aucune inscription recente</strong>
                        Les derniers enregistrements apparaitront ici.
                    </div>
                @endforelse
            </div>
        </section>
    @else
        <div class="db-kpis">
            <div class="db-card db-kpi db-kpi--primary">
                <div class="db-kpi__label">Inscriptions actives</div>
                <div class="db-kpi__value">{{ $stats['registrations'] ?? 0 }}</div>
                <div class="db-kpi__sub">dossiers visibles dans votre perimetre</div>
            </div>
            <div class="db-card db-kpi">
                <div class="db-kpi__label">Sessions visibles</div>
                <div class="db-kpi__value">{{ $stats['sessions'] ?? 0 }}</div>
                <div class="db-kpi__sub">ouvertes, fermees ou en brouillon</div>
            </div>
            <div class="db-card db-kpi">
                <div class="db-kpi__label">{{ $mode === 'center_admin' ? 'Etudiants' : 'Paiements recus' }}</div>
                <div class="db-kpi__value">{{ $mode === 'center_admin' ? ($stats['students'] ?? 0) : ($stats['paid'] ?? 0) }}</div>
                <div class="db-kpi__sub">{{ $mode === 'center_admin' ? 'dossiers suivis' : 'inscriptions payees' }}</div>
            </div>
            <div class="db-card db-kpi">
                <div class="db-kpi__label">Session ouverte</div>
                <div class="db-kpi__value">{{ $stats['current_session'] ?: 'Aucune' }}</div>
                <div class="db-kpi__sub">visible en ce moment</div>
            </div>
        </div>

        <div class="db-kpis">
            <div class="db-card db-fin db-fin--slate">
                <div class="db-fin__top">
                    <span class="db-fin__label">Montant enregistre</span>
                </div>
                <div class="db-fin__amount">{{ number_format($recordedAmount, 0, ',', ' ') }}</div>
                <div class="db-kpi__sub">FCFA sur les inscriptions actives</div>
            </div>
            <div class="db-card db-fin db-fin--green">
                <div class="db-fin__top">
                    <span class="db-fin__label">Montant collecte</span>
                    <span>{{ $collectPct }}%</span>
                </div>
                <div class="db-fin__amount">{{ number_format($collectedAmount, 0, ',', ' ') }}</div>
                <div class="db-fin__bar">
                    <div class="db-fin__fill" style="width: {{ $collectPct }}%"></div>
                </div>
                <div class="db-kpi__sub">{{ $collectPct }}% du montant enregistre</div>
            </div>
            <div class="db-card db-fin db-fin--amber">
                <div class="db-fin__top">
                    <span class="db-fin__label">Montant depose</span>
                    <span>{{ $depositPct }}%</span>
                </div>
                <div class="db-fin__amount">{{ number_format($depositedAmount, 0, ',', ' ') }}</div>
                <div class="db-fin__bar">
                    <div class="db-fin__fill" style="width: {{ $depositPct }}%"></div>
                </div>
                <div class="db-kpi__sub">{{ $depositPct }}% du montant collecte</div>
            </div>
            <div class="db-card db-fin db-fin--slate">
                <div class="db-fin__top">
                    <span class="db-fin__label">Statut sessions</span>
                </div>
                <div class="db-fin__amount">{{ ($sessionStatusSummary['open'] ?? 0) + ($sessionStatusSummary['closed'] ?? 0) + ($sessionStatusSummary['draft'] ?? 0) }}</div>
                <div class="db-kpi__sub">sessions visibles au total</div>
            </div>
        </div>

        <div class="db-grid-2">
            <section class="db-card">
                <div class="db-section__head">
                    <div>
                        <div class="db-section__title">Sessions recentes</div>
                    </div>
                    <a href="{{ route('sessions.index', request()->only('center_id')) }}" class="table-card-action">Voir tout</a>
                </div>
                <div class="db-stack">
                    @forelse ($recentSessions as $session)
                        @php $statusMeta = $statusLabels[$session->status] ?? ['label' => ucfirst((string) $session->status), 'class' => 'neutral']; @endphp
                        <a href="{{ route('sessions.show', $session) }}" class="db-session">
                            <div class="db-session__top">
                                <div>
                                    <div class="db-session__title">{{ $session->title }}</div>
                                    <div class="db-session__meta">{{ optional($session->center)->name ?: 'Centre' }} @if($session->city) - {{ $session->city }} @endif<br>{{ optional($session->exam_date)->format('d/m/Y') ?: 'Date non definie' }}</div>
                                </div>
                                <span class="badge {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</span>
                            </div>
                            <div class="db-session__stats">
                                <div class="db-mini">
                                    <div class="db-mini__label">Inscrits</div>
                                    <div class="db-mini__value">{{ $session->active_registrations_count ?? 0 }}</div>
                                </div>
                                <div class="db-mini">
                                    <div class="db-mini__label">Paiements</div>
                                    <div class="db-mini__value">{{ $session->paid_registrations_count ?? 0 }}</div>
                                </div>
                                <div class="db-mini">
                                    <div class="db-mini__label">Montant paye</div>
                                    <div class="db-mini__value">{{ number_format((float) ($session->paid_registrations_amount ?? 0), 0, ',', ' ') }} FCFA</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="db-empty">
                            <strong>Aucune session</strong>
                            Les sessions de votre perimetre apparaitront ici.
                        </div>
                    @endforelse
                </div>
            </section>
            
            <section class="db-card">
                <div class="db-section__head">
                    <div>
                        <div class="db-section__title">Dernieres inscriptions</div>
                    </div>
                    <a href="{{ route('registrations.index', request()->only('center_id', 'level')) }}" class="table-card-action">Voir tout</a>
                </div>
                <div class="db-stack">
                    @forelse ($recentRegistrations as $registration)
                        @if ($registration->payment_status === 'paid')
                        <a href="{{ route('registrations.receipt', $registration) }}" class="db-registration">
                            <div class="db-registration__top">
                                <div>
                                    <div class="db-registration__title">{{ optional($registration->student)->full_name ?: 'Candidat' }}</div>
                                    <div class="db-registration__meta">{{ optional($registration->examSession)->title ?: 'Session non definie' }}</div>
                                </div>
                                <span class="badge {{ $registration->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}
                                </span>
                            </div>
                            <div class="db-registration__meta">{{ optional($registration->center)->name ?: 'Centre non defini' }}<br>Recu : {{ $registration->receipt_number }}</div>
                            <div class="db-registration__foot">
                                <span class="td-mono">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                <span class="td-muted">{{ optional($registration->registered_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        </a>
                    @else
                        <div class="db-registration" style="opacity:0.6; cursor:not-allowed;">
                            <div class="db-registration__top">
                                <div>
                                    <div class="db-registration__title">{{ optional($registration->student)->full_name ?: 'Candidat' }}</div>
                                    <div class="db-registration__meta">{{ optional($registration->examSession)->title ?: 'Session non definie' }}</div>
                                </div>
                                <span class="badge {{ $registration->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}
                                </span>
                            </div>
                            <div class="db-registration__meta">{{ optional($registration->center)->name ?: 'Centre non defini' }}<br>Recu : {{ $registration->receipt_number }}</div>
                            <div class="db-registration__foot">
                                <span class="td-mono">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                <span class="td-muted">{{ optional($registration->registered_at)->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    @endif
                    @empty
                        <div class="db-empty">
                            <strong>Aucune inscription recente</strong>
                            Les derniers enregistrements apparaitront ici.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    @endif
</div>
@endsection

