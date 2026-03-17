@extends('layouts.app')

@section('page_title', 'Mon profil')
@section('page_kicker', 'Compte')
@section('page_subtitle', 'Consultez vos informations personnelles ainsi que votre perimetre centres.')

@push('styles')
<style>
    /* ── Hero card ── */
    .profile-hero {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        background: var(--navy);
        padding: 0;
        box-shadow: 0 24px 56px rgba(15, 23, 42, 0.18);
        margin-bottom: 20px;
    }

    /* Fond graphique */
    .profile-hero-bg {
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 80% -10%, rgba(217,75,67,0.30) 0%, transparent 65%),
            radial-gradient(ellipse 50% 60% at -10% 110%, rgba(255,255,255,0.06) 0%, transparent 55%),
            linear-gradient(135deg, #1a1a1a 0%, #2e2e2e 100%);
        pointer-events: none;
    }
    .profile-hero-bg::after {
        content: '';
        position: absolute; inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.025'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .profile-hero-inner {
        position: relative; z-index: 1;
        display: flex;
        align-items: flex-end;
        gap: 28px;
        padding: 36px 36px 30px;
        flex-wrap: wrap;
    }

    /* Avatar hero */
    .profile-avatar-hero {
        width: 88px; height: 88px;
        border-radius: 22px;
        flex-shrink: 0;
        border: 3px solid rgba(255,255,255,0.15);
        box-shadow: 0 12px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.12);
        display: grid; place-items: center;
        font-family: 'Syne', sans-serif;
        font-size: 28px; font-weight: 700; color: #fff;
        background: linear-gradient(135deg, #2a2a2a, #1a1a1a);
        overflow: hidden;
        transition: transform 200ms, box-shadow 200ms;
    }
    .profile-avatar-hero:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.14);
    }
    .profile-avatar-hero img {
        width: 100%; height: 100%; object-fit: cover; display: block;
    }

    .profile-hero-info {
        flex: 1; min-width: 200px;
    }
    .profile-hero-name {
        font-family: 'Syne', sans-serif;
        font-size: 26px; font-weight: 700;
        color: #fff; line-height: 1.1; margin-bottom: 6px;
        letter-spacing: -0.01em;
    }
    .profile-hero-email {
        font-size: 13px;
        color: rgba(255,255,255,0.48);
        margin-bottom: 14px;
    }
    .profile-hero-badges {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 999px;
        font-size: 11px; font-weight: 600;
        letter-spacing: 0.04em;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.75);
        backdrop-filter: blur(8px);
    }
    .hero-badge.active {
        background: rgba(21,128,61,0.20);
        border-color: rgba(21,128,61,0.40);
        color: #6ee7a0;
    }
    .hero-badge.blocked {
        background: rgba(153,27,27,0.20);
        border-color: rgba(153,27,27,0.40);
        color: #fca5a5;
    }
    .hero-badge-dot {
        width: 6px; height: 6px; border-radius: 50%;
    }
    .hero-badge.active  .hero-badge-dot { background: #22c55e; box-shadow: 0 0 6px #22c55e; }
    .hero-badge.blocked .hero-badge-dot { background: #ef4444; }

    /* Stats strip */
    .profile-hero-stats {
        position: relative; z-index: 1;
        display: flex;
        border-top: 1px solid rgba(255,255,255,0.07);
        padding: 0 36px;
    }
    .profile-stat {
        flex: 1;
        padding: 18px 0;
        display: flex; flex-direction: column; gap: 4px;
        border-right: 1px solid rgba(255,255,255,0.07);
        padding-right: 24px; padding-left: 0;
    }
    .profile-stat:not(:first-child) { padding-left: 24px; }
    .profile-stat:last-child { border-right: none; }
    .profile-stat-label {
        font-size: 9px; font-weight: 700; letter-spacing: 0.12em;
        text-transform: uppercase; color: rgba(255,255,255,0.28);
    }
    .profile-stat-value {
        font-family: 'Syne', sans-serif;
        font-size: 14px; font-weight: 700;
        color: rgba(255,255,255,0.82);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* ── Section wrapper ── */
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1.55fr;
        gap: 16px;
        align-items: start;
    }

    /* ── Info panel (gauche) ── */
    .info-panel {
        display: flex; flex-direction: column; gap: 16px;
    }

    .info-card {
        background: rgba(255,255,255,0.97);
        border: 1px solid rgba(216,227,244,0.9);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 14px 28px rgba(15,23,42,0.05);
    }
    .info-card-header {
        padding: 14px 20px;
        border-bottom: 1px solid rgba(216,227,244,0.8);
        background: linear-gradient(180deg, #fff, #f8faff);
        display: flex; align-items: center; gap: 10px;
    }
    .info-card-icon {
        width: 30px; height: 30px; border-radius: 9px;
        display: grid; place-items: center;
        background: rgba(26,26,26,0.07);
        color: var(--navy);
        flex-shrink: 0;
    }
    .info-card-title {
        font-family: 'Syne', sans-serif;
        font-size: 12.5px; font-weight: 700; color: var(--navy);
    }

    .info-rows { padding: 6px 0; }
    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 11px 20px;
        border-bottom: 1px solid rgba(216,227,244,0.5);
        transition: background 120ms;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row:hover { background: #f8faff; }
    .info-row-icon {
        width: 28px; height: 28px; border-radius: 8px;
        display: grid; place-items: center;
        background: rgba(216,227,244,0.4);
        color: #7a86a2; flex-shrink: 0; margin-top: 1px;
    }
    .info-row-body { flex: 1; min-width: 0; }
    .info-row-label {
        font-size: 10px; font-weight: 600; letter-spacing: 0.08em;
        text-transform: uppercase; color: #a0abc0; margin-bottom: 2px;
    }
    .info-row-value {
        font-size: 13px; font-weight: 500; color: var(--navy);
        overflow: hidden; text-overflow: ellipsis;
    }
    .info-row-value.muted { color: #a0abc0; font-weight: 400; font-style: italic; }

    /* Centers list */
    .centers-list { padding: 14px 20px; display: flex; flex-direction: column; gap: 8px; }
    .center-chip {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px; border-radius: 10px;
        background: linear-gradient(180deg, #f8faff, #eef3fb);
        border: 1px solid rgba(216,227,244,0.8);
        transition: border-color 130ms, box-shadow 130ms;
    }
    .center-chip:hover { border-color: #bfd0ef; box-shadow: 0 4px 12px rgba(31,75,143,0.08); }
    .center-chip-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--navy); flex-shrink: 0;
    }
    .center-chip-dot.primary { background: var(--red); box-shadow: 0 0 8px rgba(217,75,67,0.4); }
    .center-chip-name { font-size: 12.5px; font-weight: 500; color: var(--navy); flex: 1; }
    .center-chip-tag {
        font-size: 9.5px; font-weight: 700; letter-spacing: 0.08em;
        text-transform: uppercase; color: var(--red);
        background: var(--red-soft); padding: 2px 7px; border-radius: 99px;
    }

    /* ── Form panel (droite) ── */
    .form-panel {
        background: rgba(255,255,255,0.97);
        border: 1px solid rgba(216,227,244,0.9);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 14px 28px rgba(15,23,42,0.05);
    }

    /* Tabs */
    .form-tabs {
        display: flex;
        border-bottom: 1px solid rgba(216,227,244,0.9);
        background: linear-gradient(180deg, #fff, #f8faff);
        padding: 0 20px;
        gap: 0;
    }
    .form-tab {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 14px 16px;
        font-size: 12.5px; font-weight: 600;
        color: #a0abc0;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: color 130ms, border-color 130ms;
        white-space: nowrap;
        background: none; border-top: none; border-left: none; border-right: none;
        margin-bottom: -1px;
    }
    .form-tab:hover { color: var(--navy); }
    .form-tab.active { color: var(--navy); border-bottom-color: var(--navy); }
    .form-tab svg { flex-shrink: 0; }

    /* Tab panes */
    .tab-pane { display: none; padding: 24px; }
    .tab-pane.active { display: block; }

    /* Form sections */
    .form-section { margin-bottom: 22px; }
    .form-section:last-child { margin-bottom: 0; }
    .form-section-label {
        font-size: 10px; font-weight: 700; letter-spacing: 0.12em;
        text-transform: uppercase; color: #a0abc0;
        margin-bottom: 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-section-label::after {
        content: '';
        flex: 1; height: 1px;
        background: rgba(216,227,244,0.8);
    }
    .form-grid-2 {
        display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
    }
    .field { display: flex; flex-direction: column; gap: 5px; }
    .field label {
        font-size: 11.5px; font-weight: 600; color: #6b7a99;
        letter-spacing: 0.02em;
    }

    /* Avatar upload zone */
    .avatar-upload-zone {
        display: flex; align-items: center; gap: 16px;
        padding: 16px;
        border: 1.5px dashed rgba(216,227,244,0.9);
        border-radius: 14px;
        background: linear-gradient(180deg, #f8faff, #eef3fb);
        transition: border-color 130ms, background 130ms;
        cursor: pointer;
    }
    .avatar-upload-zone:hover {
        border-color: #6b8fd6;
        background: linear-gradient(180deg, #f0f6ff, #e4edfb);
    }
    .avatar-upload-zone input[type="file"] {
        display: none;
    }
    .avatar-preview {
        width: 52px; height: 52px; border-radius: 13px;
        overflow: hidden; flex-shrink: 0;
        background: var(--navy);
        display: grid; place-items: center;
        font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #fff;
        border: 2px solid rgba(216,227,244,0.9);
    }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar-upload-copy { flex: 1; }
    .avatar-upload-title { font-size: 13px; font-weight: 600; color: var(--navy); margin-bottom: 3px; }
    .avatar-upload-hint { font-size: 11px; color: #a0abc0; }
    .avatar-upload-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 8px;
        background: var(--white);
        border: 1px solid rgba(195,208,236,0.95);
        font-size: 12px; font-weight: 600; color: var(--navy);
        cursor: pointer; flex-shrink: 0;
        transition: background 120ms, border-color 120ms;
    }
    .avatar-upload-btn:hover { background: #eef3ff; border-color: #bfd0ef; }

    /* Form footer */
    .form-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
        padding-top: 20px;
        border-top: 1px solid rgba(216,227,244,0.8);
        margin-top: 24px;
    }

    /* Animations */
    .profile-hero,
    .profile-grid { animation: fadeUp 0.4s ease both; }
    .profile-grid { animation-delay: 0.08s; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 900px) {
        .profile-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .profile-hero-inner { padding: 24px 20px 20px; }
        .profile-hero-stats { padding: 0 20px; }
        .form-grid-2 { grid-template-columns: 1fr; }
        .profile-avatar-hero { width: 68px; height: 68px; font-size: 20px; }
        .profile-hero-name { font-size: 20px; }
    }
</style>
@endpush

@section('content')
@php
    $roleLabels = [
        'super_admin'  => 'Super admin',
        'center_admin' => 'Directeur de centre',
        'secretary'    => 'Secretaire',
    ];
    $roleLabel      = $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role));
    $assignedCenters = $user->centers ?? collect();
    $avatarUrl      = $user->avatar_path ? Storage::url($user->avatar_path) : null;
    $primaryCenter  = optional($user->center);
@endphp

<div class="form-shell wide">

    {{-- ── HERO ── --}}
    <div class="profile-hero">
        <div class="profile-hero-bg"></div>

        <div class="profile-hero-inner">
            <div class="profile-avatar-hero">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="Photo de profil">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>

            <div class="profile-hero-info">
                <div class="profile-hero-name">{{ $user->name }}</div>
                <div class="profile-hero-email">{{ $user->email }}</div>
                <div class="profile-hero-badges">
                    <span class="hero-badge">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        {{ $roleLabel }}
                    </span>
                    <span class="hero-badge {{ $user->is_blocked ? 'blocked' : 'active' }}">
                        <span class="hero-badge-dot"></span>
                        {{ $user->is_blocked ? 'Compte bloqué' : 'Compte actif' }}
                    </span>
                    @if ($primaryCenter->name)
                        <span class="hero-badge">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            {{ $primaryCenter->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="profile-hero-stats">
            <div class="profile-stat">
                <div class="profile-stat-label">Role</div>
                <div class="profile-stat-value">{{ $roleLabel }}</div>
            </div>
            <div class="profile-stat">
                <div class="profile-stat-label">Centre principal</div>
                <div class="profile-stat-value">{{ $primaryCenter->name ?: 'Global' }}</div>
            </div>
            <div class="profile-stat">
                <div class="profile-stat-label">Centres autorises</div>
                <div class="profile-stat-value">{{ $assignedCenters->count() ?: 'Portee globale' }}</div>
            </div>
            <div class="profile-stat">
                <div class="profile-stat-label">Telephone</div>
                <div class="profile-stat-value">{{ $user->phone ?: '—' }}</div>
            </div>
        </div>
    </div>

    {{-- ── GRID CONTENT ── --}}
    <div class="profile-grid">

        {{-- Colonne gauche : infos lecture seule ── --}}
        <div class="info-panel">

            {{-- Coordonnées ── --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                    </div>
                    <div class="info-card-title">Informations personnelles</div>
                </div>
                <div class="info-rows">
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="info-row-body">
                            <div class="info-row-label">Nom complet</div>
                            <div class="info-row-value">{{ $user->name }}</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div class="info-row-body">
                            <div class="info-row-label">E-mail</div>
                            <div class="info-row-value">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.13 1 .37 1.97.72 2.9a2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6 6l1.09-1.09a2 2 0 0 1 2.11-.45c.93.35 1.9.59 2.9.72a2 2 0 0 1 1.72 2z"/></svg>
                        </div>
                        <div class="info-row-body">
                            <div class="info-row-label">Telephone</div>
                            <div class="info-row-value {{ !$user->phone ? 'muted' : '' }}">
                                {{ $user->phone ?: 'Non renseigne' }}
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-row-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/><path d="M18 4l2 2-2 2"/><path d="M20 6h-3"/></svg>
                        </div>
                        <div class="info-row-body">
                            <div class="info-row-label">Role</div>
                            <div class="info-row-value">{{ $roleLabel }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Centres ── --}}
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div class="info-card-title">Centres autorisés</div>
                </div>
                <div class="centers-list">
                    @if ($primaryCenter->name)
                        <div class="center-chip">
                            <div class="center-chip-dot primary"></div>
                            <span class="center-chip-name">{{ $primaryCenter->name }}</span>
                            <span class="center-chip-tag">Principal</span>
                        </div>
                    @endif
                    @forelse ($assignedCenters->filter(fn($c) => !$primaryCenter->id || $c->id !== $primaryCenter->id) as $center)
                        <div class="center-chip">
                            <div class="center-chip-dot"></div>
                            <span class="center-chip-name">{{ $center->name }}</span>
                        </div>
                    @empty
                        @if (!$primaryCenter->name)
                            <div style="padding: 8px 0; font-size: 13px; color: #a0abc0; font-style: italic;">
                                Portée globale — tous les centres
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Colonne droite : formulaire ── --}}
        <div class="form-panel">

            <div class="form-tabs">
                <button class="form-tab active" onclick="switchTab(event,'tab-identity')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Identité & photo
                </button>
                <button class="form-tab" onclick="switchTab(event,'tab-password')">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Sécurité
                </button>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" data-loading="true" data-loading-text="Mise a jour du profil en cours...">
                @csrf
                @method('PUT')

                {{-- Onglet identité --}}
                <div class="tab-pane active" id="tab-identity">

                    <div class="form-section">
                        <div class="form-section-label">Coordonnées</div>
                        <div class="form-grid-2">
                            <div class="field">
                                <label>Nom complet</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>E-mail</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field" style="grid-column: 1 / -1;">
                                <label>Téléphone</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+237 6XX XXX XXX">
                                @error('phone')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-label">Photo de profil</div>
                        <label class="avatar-upload-zone" for="avatar-input">
                            <input type="file" name="avatar" id="avatar-input" accept="image/*" onchange="previewAvatar(event)">
                            <div class="avatar-preview" id="avatar-preview">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" id="avatar-img" alt="Avatar">
                                @else
                                    <span id="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                @endif
                            </div>
                            <div class="avatar-upload-copy">
                                <div class="avatar-upload-title">Changer la photo</div>
                                <div class="avatar-upload-hint">JPG, PNG ou WEBP · max 2 Mo</div>
                            </div>
                            <span class="avatar-upload-btn" onclick="event.preventDefault(); document.getElementById('avatar-input').click()">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Parcourir
                            </span>
                        </label>
                        @error('avatar')<div class="field-error" style="margin-top:6px;">{{ $message }}</div>@enderror

                        @if ($avatarUrl)
                            <label style="display: flex; align-items: center; gap: 8px; margin-top: 10px; font-size: 12px; color: #a0abc0; cursor: pointer;">
                                <input type="checkbox" name="remove_avatar" value="1">
                                Supprimer la photo actuelle
                            </label>
                        @endif
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>

                {{-- Onglet sécurité --}}
                <div class="tab-pane" id="tab-password">
                    <div class="form-section">
                        <div class="form-section-label">Changer le mot de passe</div>
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <div class="field">
                                <label>Mot de passe actuel</label>
                                <input type="password" name="current_password" placeholder="Requis pour valider le changement">
                                @error('current_password')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-grid-2">
                                <div class="field">
                                    <label>Nouveau mot de passe</label>
                                    <input type="password" name="password" placeholder="Minimum 8 caractères">
                                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label>Confirmation</label>
                                    <input type="password" name="password_confirmation" placeholder="Répétez le mot de passe">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 18px; padding: 14px 16px; border-radius: 12px; background: #fffbeb; border: 1px solid #fde68a; display: flex; gap: 10px; align-items: flex-start;">
                            <svg style="flex-shrink:0; color:#b45309; margin-top:1px;" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div style="font-size: 12px; color: #92400e; line-height: 1.6;">
                                Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.
                                Votre mot de passe actuel est requis pour confirmer le changement.
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Mettre à jour le mot de passe
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>{{-- /.profile-grid --}}

</div>{{-- /.form-shell --}}

@push('scripts')
<script>
    /* ── Tab switch ── */
    function switchTab(e, paneId) {
        document.querySelectorAll('.form-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        e.currentTarget.classList.add('active');
        document.getElementById(paneId).classList.add('active');
    }

    /* ── Live avatar preview ── */
    function previewAvatar(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = '<img src="' + ev.target.result + '" style="width:100%;height:100%;object-fit:cover;display:block;">';
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
@endsection
