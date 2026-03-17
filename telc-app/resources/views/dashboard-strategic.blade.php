@extends('layouts.app')

@push('styles')
<style>
.db-shell {
    display: flex;
    flex-direction: column;
    gap: 24px;
    max-height: calc(100vh - 180px);
    overflow-y: auto;
    padding-right: 8px;
}

.db-shell::-webkit-scrollbar {
    width: 6px;
}

.db-shell::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.db-shell::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.db-shell::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.db-filter {
    padding: 16px 20px;
    border: 1px solid #e5eaf4;
    border-radius: 12px;
    background: linear-gradient(180deg, #fff, #fbfcff);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
}

.db-filter form {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.db-filter__label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding-right: 16px;
    border-right: 1px solid #e7ebf3;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #7b849e;
    white-space: nowrap;
}

.db-filter select,
.db-filter input {
    min-width: 0;
    flex: 0 1 160px;
    height: 36px;
    padding: 0 12px;
    border: 1px solid #d8e1ef;
    border-radius: 8px;
    background: #f7f9fd;
    color: #132145;
    font-size: 13px;
}

.db-filter__dates {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 0 1 280px;
    min-width: 240px;
}

.db-filter__dates input {
    flex: 1;
}

.db-filter__actions {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.db-section {
    background: linear-gradient(180deg, #fff, #fbfcff);
    border: 1px solid #e7ebf3;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
    overflow: hidden;
}

.db-section-header {
    padding: 20px 24px;
    border-bottom: 1px solid #eef2f7;
    background: linear-gradient(145deg, #fff, #f8faff);
}

.db-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #102a56;
    margin-bottom: 6px;
}

.db-section-subtitle {
    font-size: 13px;
    color: #72809f;
    line-height: 1.5;
}

.db-section-content {
    padding: 20px 24px;
}

.db-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.db-kpi-card {
    padding: 20px;
    border: 1px solid #e4ebf6;
    border-radius: 12px;
    background: linear-gradient(145deg, #fff, #f8faff);
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.db-kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
}

.db-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
    border-color: #d8e1ef;
}

.db-kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.db-kpi-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #8a94ac;
}

.db-kpi-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
}

.db-kpi-badge-success {
    background: #dcfce7;
    color: #15803d;
}

.db-kpi-badge-warning {
    background: #fef3c7;
    color: #b45309;
}

.db-kpi-badge-danger {
    background: #fef2f2;
    color: #dc2626;
}

.db-kpi-value {
    font-size: 2rem;
    font-weight: 800;
    color: #102a56;
    line-height: 1;
    margin-bottom: 8px;
}

.db-kpi-detail {
    font-size: 11px;
    color: #7b849e;
    line-height: 1.4;
}

.db-kpi-progress {
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 12px;
}

.db-kpi-progress-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.db-two-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.db-three-columns {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 20px;
}

.db-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 400px;
    overflow-y: auto;
}

.db-list-item {
    padding: 16px;
    border: 1px solid #e6ecf7;
    border-radius: 10px;
    background: #fff;
    transition: all 0.2s ease;
}

.db-list-item:hover {
    border-color: #d8e1ef;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
}

.db-list-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 8px;
}

.db-list-title {
    font-size: 14px;
    font-weight: 700;
    color: #102a56;
    line-height: 1.4;
}

.db-list-meta {
    font-size: 11px;
    color: #6d7b96;
    line-height: 1.4;
}

.db-list-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    font-size: 11px;
}

.db-list-amount {
    font-weight: 700;
    color: #102a56;
}

.db-list-date {
    color: #7b849e;
}

.db-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
}

.db-badge-success {
    background: #dcfce7;
    color: #15803d;
}

.db-badge-warning {
    background: #fef3c7;
    color: #b45309;
}

.db-badge-neutral {
    background: #f3f4f6;
    color: #6b7280;
}

.db-badge-danger {
    background: #fef2f2;
    color: #dc2626;
}

.db-alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.db-alert-warning {
    background: linear-gradient(145deg, #fffbeb, #fef3c7);
    border: 1px solid #fde68a;
}

.db-alert-danger {
    background: linear-gradient(145deg, #fef2f2, #fecaca);
    border: 1px solid #fca5a5;
}

.db-alert-icon {
    font-size: 20px;
    flex-shrink: 0;
}

.db-alert-content {
    flex: 1;
}

.db-alert-title {
    font-size: 14px;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 4px;
}

.db-alert-text {
    font-size: 12px;
    color: #78350f;
    line-height: 1.5;
}

.db-alert-danger .db-alert-title {
    color: #991b1b;
}

.db-alert-danger .db-alert-text {
    color: #7f1d1d;
}

.db-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 700;
}

.db-trend-up {
    color: #15803d;
}

.db-trend-down {
    color: #dc2626;
}

.db-trend-neutral {
    color: #6b7280;
}

@media (max-width: 1200px) {
    .db-kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .db-three-columns {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .db-kpi-grid {
        grid-template-columns: 1fr;
    }
    
    .db-two-columns,
    .db-three-columns {
        grid-template-columns: 1fr;
    }
    
    .db-filter form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .db-filter__label {
        border-right: none;
        border-bottom: 1px solid #e7ebf3;
        padding-bottom: 8px;
        margin-bottom: 8px;
    }
    
    .db-filter__actions {
        margin-left: 0;
        margin-top: 8px;
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
        \App\Models\ExamSession::STATUS_CLOSED => ['label' => 'Fermée', 'class' => 'neutral'],
        \App\Models\ExamSession::STATUS_DRAFT => ['label' => 'Brouillon', 'class' => 'warning'],
    ];
@endphp

<div class="db-shell">
    <div class="db-filter">
        <form method="GET" action="{{ route('dashboard') }}">
            <div class="db-filter__label">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filtres
            </div>
            @if ($centerOptions->isNotEmpty())
                <select name="center_id"><option value="">Tous les centres</option>@foreach ($centerOptions as $center)<option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>@endforeach</select>
            @endif
            <select name="level"><option value="">Tous niveaux</option>@foreach ($levelOptions as $level)<option value="{{ $level }}" {{ request('level') === $level ? 'selected' : '' }}>{{ $level }}</option>@endforeach</select>
            <div class="db-filter__dates"><input type="date" name="date_from" value="{{ request('date_from') }}"><span>au</span><input type="date" name="date_to" value="{{ request('date_to') }}"></div>
            <div class="db-filter__actions">
                <button type="submit" class="btn btn-secondary">Appliquer</button>
                @if (request()->hasAny(['center_id', 'level', 'date_from', 'date_to']))
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost">Effacer</a>
                @endif
            </div>
        </form>
    </div>

    @if ($mode === 'super_admin')
        <!-- Tableau de bord stratégique Super Admin -->
        <div class="db-section">
            <div class="db-section-header">
                <div class="db-section-title">🎯 Tableau de bord stratégique TELC</div>
                <div class="db-section-subtitle">
                    Vue d'ensemble complète avec {{ $stats['centers'] ?? 0 }} centres, {{ $stats['students'] ?? 0 }} candidats et {{ $stats['registrations'] ?? 0 }} inscriptions.
                    @if($advanced['growth'] !== null) Performance : {{ $advanced['growth'] > 0 ? '+' : '' }}{{ $advanced['growth'] }}% d'évolution @endif
                </div>
            </div>
            <div class="db-section-content">
                <!-- KPIs stratégiques -->
                <div class="db-kpi-grid">
                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Inscriptions totales</div>
                            <div class="db-kpi-badge {{ $advanced['growth'] > 0 ? 'db-kpi-badge-success' : 'db-kpi-badge-warning' }}">
                                {{ $advanced['growth'] > 0 ? '+' : '' }}{{ $advanced['growth'] ?? 0 }}%
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ number_format($registrationsCount, 0, ',', ' ') }}</div>
                        <div class="db-kpi-detail">{{ $stats['students'] ?? 0 }} candidats uniques • {{ $stats['sessions'] ?? 0 }} sessions</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ min(100, ($registrationsCount / 1000) * 100) }}%; background: linear-gradient(90deg, #3b82f6, #8b5cf6);"></div>
                        </div>
                    </div>

                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Chiffre d'affaires</div>
                            <div class="db-kpi-badge {{ $collectPct >= 80 ? 'db-kpi-badge-success' : 'db-kpi-badge-warning' }}">
                                {{ $collectPct }}% collecté
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ number_format($recordedAmount, 0, ',', ' ') }}</div>
                        <div class="db-kpi-detail">{{ number_format($collectedAmount, 0, ',', ' ') }} FCFA collectés • {{ number_format($depositedAmount, 0, ',', ' ') }} FCFA déposés</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ $collectPct }}%; background: {{ $collectPct >= 80 ? '#22c55e' : '#f59e0b' }};"></div>
                        </div>
                    </div>

                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Centres opérationnels</div>
                            <div class="db-kpi-badge db-kpi-badge-success">
                                {{ $sessionStatusSummary['open'] ?? 0 }} actifs
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ $stats['centers'] ?? 0 }}</div>
                        <div class="db-kpi-detail">{{ $sessionStatusSummary['open'] ?? 0 }} avec sessions ouvertes • {{ $sessionStatusSummary['closed'] ?? 0 }} fermées</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ min(100, (($sessionStatusSummary['open'] ?? 0) / ($stats['centers'] ?: 1)) * 100) }}%; background: #22c55e;"></div>
                        </div>
                    </div>

                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Santé système</div>
                            <div class="db-kpi-badge {{ ($advanced['blocked_users'] ?? 0) > 0 ? 'db-kpi-badge-danger' : 'db-kpi-badge-success' }}">
                                {{ ($advanced['blocked_users'] ?? 0) > 0 ? 'Alerte' : 'OK' }}
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ ($advanced['blocked_users'] ?? 0) > 0 ? '⚠️' : '✅' }}</div>
                        <div class="db-kpi-detail">{{ $advanced['blocked_users'] ?? 0 }} comptes bloqués • {{ $depositPct }}% dépôts validés</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ ($advanced['blocked_users'] ?? 0) === 0 ? '100' : '30' }}%; background: {{ ($advanced['blocked_users'] ?? 0) === 0 ? '#22c55e' : '#ef4444' }};"></div>
                        </div>
                    </div>
                </div>

                <!-- Alertes stratégiques -->
                @if (($advanced['blocked_users'] ?? 0) > 0 || $collectPct < 80 || $depositPct < 70)
                <div class="db-alert {{ ($advanced['blocked_users'] ?? 0) > 0 ? 'db-alert-danger' : 'db-alert-warning' }}">
                    <div class="db-alert-icon">{{ ($advanced['blocked_users'] ?? 0) > 0 ? '⚠️' : '💰' }}</div>
                    <div class="db-alert-content">
                        <div class="db-alert-title">Actions requises</div>
                        <div class="db-alert-text">
                            @if (($advanced['blocked_users'] ?? 0) > 0)
                                • {{ $advanced['blocked_users'] }} utilisateur{{ ($advanced['blocked_users'] ?? 0) > 1 ? 's' : '' }} bloqué{{ ($advanced['blocked_users'] ?? 0) > 1 ? 's' : '' }}<br>
                            @endif
                            @if ($collectPct < 80)
                                • Taux de collecte à {{ $collectPct }}% (objectif: 80%+)<br>
                            @endif
                            @if ($depositPct < 70)
                                • Seulement {{ $depositPct }}% des fonds déposés<br>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Analyse par dimension -->
        <div class="db-two-columns">
            <div class="db-section">
                <div class="db-section-header">
                    <div class="db-section-title">🏆 Classement des centres</div>
                    <div class="db-section-subtitle">
                        Performance par volume d'inscriptions et revenus générés.
                    </div>
                </div>
                <div class="db-section-content">
                    <div class="db-list">
                        @forelse ($advanced['by_center'] as $row)
                            @php $percent = $registrationsCount > 0 ? round(($row->total / $registrationsCount) * 100) : 0; @endphp
                            @php $revenue = $row->total * ($recordedAmount / $registrationsCount); @endphp
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">{{ $row->name }}</div>
                                        <div class="db-list-meta">{{ $row->total }} inscriptions • {{ number_format($revenue, 0, ',', ' ') }} FCFA</div>
                                    </div>
                                    <div class="db-badge db-badge-success">{{ $percent }}%</div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ $row->total }} candidats</span>
                                    <span class="db-list-date">{{ number_format($revenue, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                        @empty
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">Aucune donnée</div>
                                        <div class="db-list-meta">Aucun centre avec des inscriptions</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="db-section">
                <div class="db-section-header">
                    <div class="db-section-title">📊 Répartition par niveaux TELC</div>
                    <div class="db-section-subtitle">
                        Analyse des inscriptions par niveau (B1, B2, C1).
                    </div>
                </div>
                <div class="db-section-content">
                    <div class="db-list">
                        @forelse ($advanced['by_level'] as $row)
                            @php $percent = $registrationsCount > 0 ? round(($row->total / $registrationsCount) * 100) : 0; @endphp
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">Niveau {{ $row->level }}</div>
                                        <div class="db-list-meta">{{ $row->total }} inscriptions • {{ $percent }}% du total</div>
                                    </div>
                                    <div class="db-badge {{ $row->level === 'B2' ? 'db-badge-success' : ($row->level === 'B1' ? 'db-badge-warning' : 'db-badge-neutral') }}">{{ $row->level }}</div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ $row->total }} places</span>
                                    <span class="db-list-date">{{ $percent }}% de part</span>
                                </div>
                            </div>
                        @empty
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">Aucune donnée</div>
                                        <div class="db-list-meta">Aucune inscription par niveau</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Évolution temporelle -->
        <div class="db-section">
            <div class="db-section-header">
                <div class="db-section-title">📈 Évolution des revenus mensuels</div>
                <div class="db-section-subtitle">
                    Tendance sur 6 derniers mois pour identifier les patterns saisonniers.
                </div>
            </div>
            <div class="db-section-content">
                <div class="db-list">
                    @forelse ($advanced['monthly_revenue']->reverse()->take(6) as $key => $row)
                        @php $previous = $key > 0 ? $advanced['monthly_revenue']->reverse()->take(6)[$key - 1] : null; @endphp
                        @php $trend = $previous && $previous->total > 0 ? round((($row->total - $previous->total) / $previous->total) * 100) : 0; @endphp
                        <div class="db-list-item">
                            <div class="db-list-header">
                                <div>
                                    <div class="db-list-title">{{ \Carbon\Carbon::createFromFormat('Y-m', $row->period)->format('F Y') }}</div>
                                    <div class="db-list-meta">{{ number_format((float) $row->total, 0, ',', ' ') }} FCFA • {{ round(($row->total / $recordedAmount) * 100) }}% du total annuel</div>
                                </div>
                                <div class="db-badge {{ $trend > 0 ? 'db-badge-success' : ($trend < 0 ? 'db-badge-danger' : 'db-badge-neutral') }}">
                                    {{ $trend > 0 ? '+' : '' }}{{ $trend }}%
                                </div>
                            </div>
                            <div class="db-list-footer">
                                <span class="db-list-amount">{{ number_format((float) $row->total, 0, ',', ' ') }} FCFA</span>
                                <span class="db-list-date">{{ $trend > 0 ? '↗️' : ($trend < 0 ? '↘️' : '→') }} {{ abs($trend) }}% vs mois précédent</span>
                            </div>
                        </div>
                    @empty
                        <div class="db-list-item">
                            <div class="db-list-header">
                                <div>
                                    <div class="db-list-title">Aucune donnée mensuelle</div>
                                    <div class="db-list-meta">Pas assez d'historique pour analyser les tendances</div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sessions critiques et activité récente -->
        <div class="db-two-columns">
            <div class="db-section">
                <div class="db-section-header">
                    <div class="db-section-title">🎓 Sessions d'examen actives</div>
                    <div class="db-section-subtitle">
                        Sessions ouvertes avec taux de remplissage.
                    </div>
                </div>
                <div class="db-section-content">
                    <div class="db-list">
                        @forelse ($recentSessions as $session)
                            @php $statusMeta = $statusLabels[$session->status] ?? ['label' => ucfirst((string) $session->status), 'class' => 'neutral']; @endphp
                            @php $fillRate = $session->price > 0 ? round((($session->active_registrations_amount ?? 0) / ($session->price * 50)) * 100) : 0; @endphp
                            <a href="{{ route('sessions.show', $session) }}" class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">{{ $session->title }}</div>
                                        <div class="db-list-meta">
                                            {{ optional($session->center)->name }} • {{ $session->level }} • 
                                            {{ optional($session->exam_date)->format('d/m/Y') ?: 'Date non définie' }}<br>
                                            {{ $session->active_registrations_count ?? 0 }} places • {{ $fillRate }}% rempli
                                        </div>
                                    </div>
                                    <div class="db-badge {{ $statusMeta['class'] === 'success' ? 'db-badge-success' : ($statusMeta['class'] === 'warning' ? 'db-badge-warning' : 'db-badge-neutral') }}">
                                        {{ $statusMeta['label'] }}
                                    </div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ number_format($session->active_registrations_amount ?? 0, 0, ',', ' ') }} FCFA</span>
                                    <span class="db-list-date">{{ $session->paid_registrations_count ?? 0 }} payés</span>
                                </div>
                            </a>
                        @empty
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">Aucune session active</div>
                                        <div class="db-list-meta">Aucune session d'examen en cours</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="db-section">
                <div class="db-section-header">
                    <div class="db-section-title">👥 Dernières inscriptions</div>
                    <div class="db-section-subtitle">
                        Activité récente avec validation des paiements.
                    </div>
                </div>
                <div class="db-section-content">
                    <div class="db-list">
                        @forelse ($recentRegistrations as $registration)
                            @if ($registration->payment_status === 'paid')
                            <a href="{{ route('registrations.receipt', $registration) }}" class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">{{ optional($registration->student)->full_name ?: 'Candidat inconnu' }}</div>
                                        <div class="db-list-meta">
                                            {{ optional($registration->examSession)->title }} • {{ optional($registration->center)->name }}<br>
                                            N°{{ $registration->receipt_number }} • {{ optional($registration->registered_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="db-badge {{ $registration->payment_status === 'paid' ? 'db-badge-success' : 'db-badge-warning' }}">
                                        {{ $registration->payment_status === 'paid' ? 'Payé' : 'En attente' }}
                                    </div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                    <span class="db-list-date">{{ optional($registration->registered_at)->diffForHumans() }}</span>
                                </div>
                            </a>
                        @else
                            <div class="db-list-item" style="opacity:0.6; cursor:not-allowed;">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">{{ optional($registration->student)->full_name ?: 'Candidat inconnu' }}</div>
                                        <div class="db-list-meta">
                                            {{ optional($registration->examSession)->title }} • {{ optional($registration->center)->name }}<br>
                                            N°{{ $registration->receipt_number }} • {{ optional($registration->registered_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="db-badge {{ $registration->payment_status === 'paid' ? 'db-badge-success' : 'db-badge-warning' }}">
                                        {{ $registration->payment_status === 'paid' ? 'Payé' : 'En attente' }}
                                    </div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                    <span class="db-list-date">{{ optional($registration->registered_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif
                        @empty
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">Aucune activité récente</div>
                                        <div class="db-list-meta">Aucune inscription enregistrée</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Vue Centre Admin/Secrétaire -->
        <div class="db-section">
            <div class="db-section-header">
                <div class="db-section-title">🏢 Tableau de bord du centre</div>
                <div class="db-section-subtitle">
                    Vue d'opérationnelle de votre centre avec {{ $stats['registrations'] ?? 0 }} inscriptions et {{ $stats['sessions'] ?? 0 }} sessions.
                    @if($stats['current_session']) Session actuelle : {{ $stats['current_session'] }} @endif
                </div>
            </div>
            <div class="db-section-content">
                <!-- KPIs du centre -->
                <div class="db-kpi-grid">
                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Inscriptions du centre</div>
                            <div class="db-kpi-badge {{ $collectPct >= 80 ? 'db-kpi-badge-success' : 'db-kpi-badge-warning' }}">
                                {{ $collectPct }}% payé
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ number_format($stats['registrations'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="db-kpi-detail">{{ $mode === 'center_admin' ? ($stats['students'] ?? 0) : '0' }} étudiants • {{ $stats['sessions'] ?? 0 }} sessions</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ min(100, (($stats['registrations'] ?? 0) / 200) * 100) }}%; background: linear-gradient(90deg, #3b82f6, #8b5cf6);"></div>
                        </div>
                    </div>

                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Revenus générés</div>
                            <div class="db-kpi-badge {{ $depositPct >= 70 ? 'db-kpi-badge-success' : 'db-kpi-badge-warning' }}">
                                {{ $depositPct }}% déposé
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ number_format($recordedAmount, 0, ',', ' ') }}</div>
                        <div class="db-kpi-detail">{{ number_format($collectedAmount, 0, ',', ' ') }} FCFA collectés • {{ number_format($depositedAmount, 0, ',', ' ') }} FCFA déposés</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ $collectPct }}%; background: {{ $collectPct >= 80 ? '#22c55e' : '#f59e0b' }};"></div>
                        </div>
                    </div>

                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Sessions actives</div>
                            <div class="db-kpi-badge db-kpi-badge-success">
                                {{ $sessionStatusSummary['open'] ?? 0 }} ouvertes
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ $sessionStatusSummary['open'] ?? 0 }}</div>
                        <div class="db-kpi-detail">{{ $sessionStatusSummary['closed'] ?? 0 }} fermées • {{ $sessionStatusSummary['draft'] ?? 0 }} brouillons</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ min(100, (($sessionStatusSummary['open'] ?? 0) / ($stats['sessions'] ?: 1)) * 100) }}%; background: #22c55e;"></div>
                        </div>
                    </div>

                    <div class="db-kpi-card">
                        <div class="db-kpi-header">
                            <div class="db-kpi-title">Performance actuelle</div>
                            <div class="db-kpi-badge {{ $collectPct >= 80 && $depositPct >= 70 ? 'db-kpi-badge-success' : 'db-kpi-badge-warning' }}">
                                {{ $collectPct >= 80 && $depositPct >= 70 ? 'OK' : 'Action' }}
                            </div>
                        </div>
                        <div class="db-kpi-value">{{ $collectPct >= 80 && $depositPct >= 70 ? '✅' : '⚠️' }}</div>
                        <div class="db-kpi-detail">{{ number_format($recordedAmount / ($stats['registrations'] ?: 1), 0, ',', ' ') }} FCFA par inscription</div>
                        <div class="db-kpi-progress">
                            <div class="db-kpi-progress-fill" style="width: {{ ($collectPct >= 80 && $depositPct >= 70) ? '100' : '60' }}%; background: {{ ($collectPct >= 80 && $depositPct >= 70) ? '#22c55e' : '#f59e0b' }};"></div>
                        </div>
                    </div>
                </div>

                <!-- Alertes du centre -->
                @if ($collectPct < 80 || $depositPct < 70 || ($sessionStatusSummary['open'] ?? 0) === 0)
                <div class="db-alert db-alert-warning">
                    <div class="db-alert-icon">⚠️</div>
                    <div class="db-alert-content">
                        <div class="db-alert-title">Actions requises pour votre centre</div>
                        <div class="db-alert-text">
                            @if ($collectPct < 80)
                                • Taux de paiement à {{ $collectPct }}% (objectif: 80%+)<br>
                            @endif
                            @if ($depositPct < 70)
                                • Seulement {{ $depositPct }}% des fonds déposés<br>
                            @endif
                            @if (($sessionStatusSummary['open'] ?? 0) === 0)
                                • Aucune session ouverte aux inscriptions<br>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sessions et inscriptions du centre -->
        <div class="db-two-columns">
            <div class="db-section">
                <div class="db-section-header">
                    <div class="db-section-title">🎓 Sessions du centre</div>
                    <div class="db-section-subtitle">
                        Sessions d'examen avec taux de remplissage.
                    </div>
                    <a href="{{ route('sessions.index', request()->only('center_id')) }}" class="btn btn-sm btn-secondary">Voir tout</a>
                </div>
                <div class="db-section-content">
                    <div class="db-list">
                        @forelse ($recentSessions as $session)
                            @php $statusMeta = $statusLabels[$session->status] ?? ['label' => ucfirst((string) $session->status), 'class' => 'neutral']; @endphp
                            @php $fillRate = $session->price > 0 ? round((($session->active_registrations_amount ?? 0) / ($session->price * 50)) * 100) : 0; @endphp
                            <a href="{{ route('sessions.show', $session) }}" class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">{{ $session->title }}</div>
                                        <div class="db-list-meta">
                                            {{ $session->level }} • {{ optional($session->exam_date)->format('d/m/Y') ?: 'Date non définie' }}<br>
                                            {{ $session->active_registrations_count ?? 0 }} places • {{ $fillRate }}% rempli
                                        </div>
                                    </div>
                                    <div class="db-badge {{ $statusMeta['class'] === 'success' ? 'db-badge-success' : ($statusMeta['class'] === 'warning' ? 'db-badge-warning' : 'db-badge-neutral') }}">
                                        {{ $statusMeta['label'] }}
                                    </div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ number_format($session->active_registrations_amount ?? 0, 0, ',', ' ') }} FCFA</span>
                                    <span class="db-list-date">{{ $session->paid_registrations_count ?? 0 }} payés</span>
                                </div>
                            </a>
                        @empty
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">Aucune session</div>
                                        <div class="db-list-meta">Aucune session d'examen trouvée</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="db-section">
                <div class="db-section-header">
                    <div class="db-section-title">👥 Dernières inscriptions</div>
                    <div class="db-section-subtitle">
                        Activité récente du centre.
                    </div>
                    <a href="{{ route('registrations.index', request()->only('center_id', 'level')) }}" class="btn btn-sm btn-secondary">Voir tout</a>
                </div>
                <div class="db-section-content">
                    <div class="db-list">
                        @forelse ($recentRegistrations as $registration)
                            @if ($registration->payment_status === 'paid')
                            <a href="{{ route('registrations.receipt', $registration) }}" class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">{{ optional($registration->student)->full_name ?: 'Candidat inconnu' }}</div>
                                        <div class="db-list-meta">
                                            {{ optional($registration->examSession)->title }}<br>
                                            N°{{ $registration->receipt_number }} • {{ optional($registration->registered_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="db-badge {{ $registration->payment_status === 'paid' ? 'db-badge-success' : 'db-badge-warning' }}">
                                        {{ $registration->payment_status === 'paid' ? 'Payé' : 'En attente' }}
                                    </div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                    <span class="db-list-date">{{ optional($registration->registered_at)->diffForHumans() }}</span>
                                </div>
                            </a>
                        @else
                            <div class="db-list-item" style="opacity:0.6; cursor:not-allowed;">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">{{ optional($registration->student)->full_name ?: 'Candidat inconnu' }}</div>
                                        <div class="db-list-meta">
                                            {{ optional($registration->examSession)->title }}<br>
                                            N°{{ $registration->receipt_number }} • {{ optional($registration->registered_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="db-badge {{ $registration->payment_status === 'paid' ? 'db-badge-success' : 'db-badge-warning' }}">
                                        {{ $registration->payment_status === 'paid' ? 'Payé' : 'En attente' }}
                                    </div>
                                </div>
                                <div class="db-list-footer">
                                    <span class="db-list-amount">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</span>
                                    <span class="db-list-date">{{ optional($registration->registered_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif
                        @empty
                            <div class="db-list-item">
                                <div class="db-list-header">
                                    <div>
                                        <div class="db-list-title">Aucune activité</div>
                                        <div class="db-list-meta">Aucune inscription récente</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

