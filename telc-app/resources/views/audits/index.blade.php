@extends('layouts.app')

@section('page_title', 'Historique des actions')
@section('page_kicker', 'Systeme')
@section('page_subtitle', 'Suivez toutes les actions et les heures de connexion. Filtrez par utilisateur ou par periode.')

@push('styles')
<style>
    /* -- Avatar fix global -- */
    .user-line {
        display: inline-flex;
        align-items: center;
        gap: 9px;
    }
    .user-line .user-avatar-chip {
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 8px;
        display: grid;
        place-items: center;
    }
    .user-line .user-avatar-chip img,
    .user-line .user-avatar-chip .user-avatar-img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        border-radius: inherit;
    }
    .user-line > img {
        flex-shrink: 0; object-fit: cover;
        display: block; border-radius: 8px;
    }

    /* -- Stats strip -- */
    .activity-stats {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .activity-stat-card {
        padding: 18px 20px;
        border-radius: 18px;
        border: 1px solid rgba(216,227,244,0.9);
        background: rgba(255,255,255,0.97);
        box-shadow: 0 10px 24px rgba(15,23,42,0.05);
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: transform 140ms, box-shadow 140ms;
    }
    .activity-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 32px rgba(15,23,42,0.08);
    }
    .activity-stat-top {
        display: flex; align-items: center; justify-content: space-between;
    }
    .activity-stat-icon {
        width: 34px; height: 34px; border-radius: 10px;
        display: grid; place-items: center; flex-shrink: 0;
    }
    .activity-stat-icon.ink    { background: rgba(26,26,26,0.08);    color: var(--navy); }
    .activity-stat-icon.green  { background: rgba(21,128,61,0.10);   color: #15803d; }
    .activity-stat-icon.blue   { background: rgba(31,75,143,0.10);   color: #1f4b8f; }
    .activity-stat-icon.amber  { background: rgba(180,83,9,0.10);    color: #b45309; }
    .activity-stat-icon.red    { background: rgba(217,75,67,0.10);   color: var(--red); }
    .activity-stat-label {
        font-size: 9.5px; font-weight: 700; letter-spacing: 0.1em;
        text-transform: uppercase; color: #a0abc0;
    }
    .activity-stat-value {
        font-family: 'Syne', sans-serif;
        font-size: 26px; font-weight: 700;
        color: var(--navy); line-height: 1;
        letter-spacing: -0.02em;
    }

    /* -- Filter card -- */
    .filter-card {
        background: rgba(255,255,255,0.97);
        border: 1px solid rgba(216,227,244,0.9);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(15,23,42,0.05);
        margin-bottom: 20px;
    }
    .filter-card-header {
        padding: 14px 20px;
        border-bottom: 1px solid rgba(216,227,244,0.8);
        background: linear-gradient(180deg, #fff, #f8faff);
        display: flex; align-items: center; gap: 10px;
    }
    .filter-icon {
        width: 30px; height: 30px; border-radius: 9px;
        display: grid; place-items: center;
        background: rgba(26,26,26,0.07); color: var(--navy);
        flex-shrink: 0;
    }
    .filter-card-title {
        font-family: 'Syne', sans-serif;
        font-size: 12.5px; font-weight: 700; color: var(--navy);
    }
    .filter-body {
        padding: 20px;
    }
    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        align-items: flex-end;
    }
    .filter-field { display: flex; flex-direction: column; gap: 5px; }
    .filter-field label {
        font-size: 11px; font-weight: 600; color: #7a86a2;
        letter-spacing: 0.04em; text-transform: uppercase;
    }

    /* -- Table card -- */
    .activity-table-card {
        background: rgba(255,255,255,0.97);
        border: 1px solid rgba(216,227,244,0.9);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 14px 30px rgba(15,23,42,0.06);
    }
    .activity-table-header {
        padding: 16px 20px;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid rgba(216,227,244,0.9);
        background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(246,249,255,0.96));
    }
    .activity-table-title {
        font-family: 'Syne', sans-serif;
        font-size: 13px; font-weight: 700; color: var(--navy);
    }

    /* Type badge colors */
    .type-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 999px;
        font-size: 10px; font-weight: 700; white-space: nowrap;
        letter-spacing: 0.05em;
    }
    .type-badge::before {
        content: ''; width: 5px; height: 5px; border-radius: 50%;
    }
    .type-badge.login       { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .type-badge.login::before       { background: #22c55e; }
    .type-badge.registration { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .type-badge.registration::before { background: #3b82f6; }
    .type-badge.deposit     { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .type-badge.deposit::before     { background: #f59e0b; }
    .type-badge.user        { background: #fdf4ff; color: #7e22ce; border: 1px solid #e9d5ff; }
    .type-badge.user::before        { background: #a855f7; }\r\n    .type-badge.center      { background: #f0f9ff; color: #075985; border: 1px solid #bae6fd; }\r\n    .type-badge.center::before      { background: #38bdf8; }\r\n    .type-badge.session     { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }\r\n    .type-badge.session::before     { background: #f87171; }
    .type-badge.default     { background: linear-gradient(180deg,#f7f9ff,#ebf1ff); color: #667085; border: 1px solid #dbe5f8; }
    .type-badge.default::before     { background: #7b849e; }

    /* Row hover effet */
    tbody tr { transition: background 100ms; }
    tbody tr:hover { background: #f8fbff; }

    /* Timeline dot */
    .timeline-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #d1d9ed; flex-shrink: 0; margin-top: 2px;
        transition: background 120ms;
    }
    tbody tr:hover .timeline-dot { background: var(--navy); }

    /* Animations */
    .activity-stats,
    .filter-card,
    .activity-table-card {
        animation: fadeUp 0.35s ease both;
    }
    .filter-card         { animation-delay: 0.06s; }
    .activity-table-card { animation-delay: 0.12s; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .activity-stats { grid-template-columns: repeat(3, 1fr); }
        .filter-grid    { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
        .activity-stats { grid-template-columns: repeat(2, 1fr); }
        .filter-grid    { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-shell stack-grid">

    {{-- Stats --}}
    <div class="activity-stats">
        <div class="activity-stat-card">
            <div class="activity-stat-top">
                <div class="activity-stat-label">Total</div>
                <div class="activity-stat-icon ink">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
            </div>
            <div class="activity-stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="activity-stat-card">
            <div class="activity-stat-top">
                <div class="activity-stat-label">Connexions</div>
                <div class="activity-stat-icon green">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                </div>
            </div>
            <div class="activity-stat-value">{{ $stats['logins'] }}</div>
        </div>
        <div class="activity-stat-card">
            <div class="activity-stat-top">
                <div class="activity-stat-label">Inscriptions</div>
                <div class="activity-stat-icon blue">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                </div>
            </div>
            <div class="activity-stat-value">{{ $stats['registrations'] }}</div>
        </div>
        <div class="activity-stat-card">
            <div class="activity-stat-top">
                <div class="activity-stat-label">Dépôts</div>
                <div class="activity-stat-icon amber">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="activity-stat-value">{{ $stats['deposits'] }}</div>
        </div>
        <div class="activity-stat-card">
            <div class="activity-stat-top">
                <div class="activity-stat-label">Comptes</div>
                <div class="activity-stat-icon red">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </div>
            </div>
            <div class="activity-stat-value">{{ $stats['users'] }}</div>
        </div>
        <div class="activity-stat-card">
            <div class="activity-stat-top">
                <div class="activity-stat-label">Centres</div>
                <div class="activity-stat-icon blue">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 4v16"/><path d="M16 4v16"/></svg>
                </div>
            </div>
            <div class="activity-stat-value">{{ $stats['centers'] }}</div>
        </div>
        <div class="activity-stat-card">
            <div class="activity-stat-top">
                <div class="activity-stat-label">Sessions</div>
                <div class="activity-stat-icon amber">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
            </div>
            <div class="activity-stat-value">{{ $stats['sessions'] }}</div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="filter-card">
        <div class="filter-card-header">
            <div class="filter-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            </div>
            <div class="filter-card-title">Filtrer l'historique</div>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('activity.index') }}">
                <div class="filter-grid">
                    <div class="filter-field">
                        <label>Utilisateur</label>
                        <select name="user_id">
                            <option value="">Tous les utilisateurs</option>
                            @foreach ($users as $filterUser)
                                <option value="{{ $filterUser->id }}" {{ (string) $selectedUserId === (string) $filterUser->id ? 'selected' : '' }}>
                                    {{ $filterUser->name }} ({{ $filterUser->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <label>Du</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}">
                    </div>
                    <div class="filter-field">
                        <label>Au</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}">
                    </div>
                    <div style="display: flex; gap: 8px; align-items: flex-end;">
                        <a class="btn btn-ghost" href="{{ route('activity.index') }}" style="height:42px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.51"/></svg>
                        </a>
                        <button class="btn btn-primary" type="submit" style="height:42px; flex: 1;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="activity-table-card">
        <div class="activity-table-header">
            <div>
                <div class="activity-table-title">Journal détaillé</div>
                <div class="td-muted" style="font-size:11.5px; margin-top:2px;">De la plus récente à la plus ancienne</div>
            </div>
            @if ($entries->total() ?? false)
                <span class="badge neutral">{{ $entries->total() }} entrée{{ $entries->total() > 1 ? 's' : '' }}</span>
            @endif
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Type</th>
                        <th>Action</th>
                        <th>Utilisateur</th>
                        <th>Date</th>
                        <th>Détails</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entries as $entry)
                        @php
                            $typeRaw = strtolower($entry['type'] ?? '');
                            if (str_contains($typeRaw, 'login') || str_contains($typeRaw, 'connexion')) {
                                $typeCss = 'login';
                            } elseif (str_contains($typeRaw, 'registr') || str_contains($typeRaw, 'inscript')) {
                                $typeCss = 'registration';
                            } elseif (str_contains($typeRaw, 'deposit') || str_contains($typeRaw, 'depot')) {
                                $typeCss = 'deposit';
                            } elseif (str_contains($typeRaw, 'user') || str_contains($typeRaw, 'compte')) {
                                $typeCss = 'user';
                            } elseif (str_contains($typeRaw, 'centre')) {
                                $typeCss = 'center';
                            } elseif (str_contains($typeRaw, 'session')) {
                                $typeCss = 'session';
                            } else {
                                $typeCss = 'default';
                            }
                        @endphp
                        <tr>
                            <td style="width:24px; padding-right:0;">
                                <div class="timeline-dot"></div>
                            </td>
                            <td>
                                <span class="type-badge {{ $typeCss }}">{{ $entry['type'] }}</span>
                            </td>
                            <td style="font-size:12.5px; font-weight:500; color:var(--navy);">
                                {{ $entry['action'] }}
                            </td>
                            <td>
                                <div class="user-line">
                                    @include('partials.user-avatar', ['user' => $entry['user'], 'size' => 28])
                                    <div>
                                        <div style="font-size:12.5px; font-weight:600; color:var(--navy);">{{ $entry['user_name'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-muted td-mono" style="white-space:nowrap;">
                                <div style="font-size:12px;">{{ optional($entry['created_at'])->format('d/m/Y') }}</div>
                                <div style="font-size:11px; color:#b0bcd0;">{{ optional($entry['created_at'])->format('H:i') }}</div>
                            </td>
                            <td class="td-muted" style="max-width:260px;">
                                <span style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $entry['details'] ?: '—' }}
                                </span>
                            </td>
                            <td class="td-muted td-mono" style="font-size:11.5px;">
                                {{ $entry['ip_address'] ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                    </div>
                                    <div class="empty-state-title">Aucune activité</div>
                                    <div class="empty-state-desc">Aucune action ne correspond aux filtres sélectionnés. Essayez d'élargir la période.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($entries->hasPages())
            <div class="pagination">
                <div class="pagination-bar">
                    <div class="page-links">
                        {{-- Previous --}}
                        @if ($entries->onFirstPage())
                            <span class="page-link is-disabled">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                            </span>
                        @else
                            <a class="page-link" href="{{ $entries->previousPageUrl() }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                            </a>
                        @endif

                        {{-- Pages --}}
                        @foreach ($entries->getUrlRange(max(1, $entries->currentPage()-2), min($entries->lastPage(), $entries->currentPage()+2)) as $page => $url)
                            <a class="page-link {{ $page === $entries->currentPage() ? 'active' : '' }}" href="{{ $url }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        {{-- Next --}}
                        @if ($entries->hasMorePages())
                            <a class="page-link" href="{{ $entries->nextPageUrl() }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        @else
                            <span class="page-link is-disabled">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </span>
                        @endif
                    </div>
                    <div class="page-info">
                        Page {{ $entries->currentPage() }} / {{ $entries->lastPage() }}
                        &nbsp;·&nbsp; {{ $entries->total() }} entrée{{ $entries->total() > 1 ? 's' : '' }}
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection



