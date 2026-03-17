@extends('layouts.app')

@section('page_title', 'Modifier un centre')
@section('page_kicker', 'Pilotage')
@section('page_subtitle', 'Modifiez les informations du centre.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('centers.index') }}">Retour</a>
@endsection

@php
    if (!function_exists('safeOld')) {
        function safeOld($key, $default = '') {
            $value = old($key, $default);
            return is_array($value) ? '' : $value;
        }
    }
@endphp
@section('content')

<style>
    /* ── Reset & Base ──────────────────────────────── */
    .center-edit-shell * { box-sizing: border-box; }

    .center-edit-shell {
        max-width: 820px;
        margin: 0 auto;
        padding: 2rem 1rem 3rem;
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
    }

    /* ── Page Header ──────────────────────────────── */
    .ce-header {
        margin-bottom: 2rem;
        animation: ce-fade-up .4s ease both;
    }

    .ce-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #888;
        text-decoration: none;
        margin-bottom: 1.25rem;
        transition: color .15s;
    }

    .ce-back:hover { color: #222; }

    .ce-back svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    .ce-page-title {
        font-size: 24px;
        font-weight: 600;
        color: #111;
        letter-spacing: -.3px;
        margin-bottom: 4px;
    }

    .ce-page-sub {
        font-size: 14px;
        color: #888;
    }

    .ce-page-sub strong {
        color: #444;
        font-weight: 500;
    }

    /* ── Card ──────────────────────────────────────── */
    .ce-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 16px;
        overflow: hidden;
        animation: ce-fade-up .45s .05s ease both;
        box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.04);
    }

    /* ── Card Header ───────────────────────────────── */
    .ce-card-header {
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fafafa;
    }

    .ce-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #EFF6FF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .ce-card-icon svg {
        width: 20px;
        height: 20px;
        color: #2563EB;
    }

    .ce-card-title {
        font-size: 15px;
        font-weight: 600;
        color: #111;
    }

    .ce-card-note {
        font-size: 12px;
        color: #999;
        margin-top: 2px;
    }

    /* ── Card Body ─────────────────────────────────── */
    .ce-card-body {
        padding: 1.75rem;
    }

    /* ── Section labels ────────────────────────────── */
    .ce-section {
        margin-bottom: 1.5rem;
    }

    .ce-section-label {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #aaa;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ce-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f0f0f0;
    }

    /* ── Form grid ─────────────────────────────────── */
    .ce-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .ce-row.full {
        grid-template-columns: 1fr;
    }

    .ce-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    /* ── Labels ────────────────────────────────────── */
    .ce-label {
        font-size: 13px;
        font-weight: 500;
        color: #555;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .ce-label .req {
        color: #EF4444;
        font-size: 14px;
        line-height: 1;
    }

    /* ── Inputs ────────────────────────────────────── */
    .ce-input,
    .ce-select,
    .ce-textarea {
        font-family: inherit;
        font-size: 14px;
        color: #111;
        background: #fff;
        border: 1px solid #e2e2e2;
        border-radius: 10px;
        padding: 9px 13px;
        width: 100%;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        -webkit-appearance: none;
        appearance: none;
    }

    .ce-input:hover,
    .ce-select:hover,
    .ce-textarea:hover {
        border-color: #ccc;
    }

    .ce-input:focus,
    .ce-select:focus,
    .ce-textarea:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }

    .ce-textarea {
        resize: vertical;
        min-height: 88px;
        line-height: 1.6;
    }

    /* Custom select arrow */
    .ce-select-wrap {
        position: relative;
    }

    .ce-select-wrap::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #888;
        pointer-events: none;
    }

    .ce-select {
        padding-right: 32px;
        cursor: pointer;
    }

    /* ── Status badge ──────────────────────────────── */
    .ce-status-wrap {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .ce-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 12.5px;
        font-weight: 500;
        align-self: flex-start;
        transition: background .2s, color .2s;
    }

    .ce-status-badge.active {
        background: #F0FDF4;
        color: #15803D;
    }

    .ce-status-badge.inactive {
        background: #F4F4F5;
        color: #71717A;
    }

    .ce-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
        transition: background .2s;
    }

    .ce-dot.active { background: #22C55E; }
    .ce-dot.inactive { background: #A1A1AA; }

    /* ── Divider ───────────────────────────────────── */
    .ce-divider {
        height: 1px;
        background: #f4f4f4;
        margin: 1.5rem 0;
    }

    /* ── Card Footer / Actions ─────────────────────── */
    .ce-card-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 1.25rem 1.75rem;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .ce-btn {
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        padding: 9px 20px;
        border-radius: 10px;
        cursor: pointer;
        border: 1px solid #e2e2e2;
        background: #fff;
        color: #444;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background .15s, border-color .15s, box-shadow .15s, transform .1s;
    }

    .ce-btn:hover {
        background: #f6f6f6;
        border-color: #ccc;
    }

    .ce-btn:active { transform: scale(.98); }

    .ce-btn-primary {
        background: #111;
        color: #fff;
        border-color: #111;
    }

    .ce-btn-primary:hover {
        background: #222;
        border-color: #222;
    }

    .ce-btn-primary svg,
    .ce-btn svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
    }

    /* ── Animations ────────────────────────────────── */
    @keyframes ce-fade-up {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Error messages ────────────────────────────── */
    .ce-error {
        font-size: 12px;
        color: #EF4444;
        margin-top: 2px;
    }

    .ce-input.is-error,
    .ce-select.is-error,
    .ce-textarea.is-error {
        border-color: #FCA5A5;
    }

    /* ── Responsive ────────────────────────────────── */
    @media (max-width: 600px) {
        .ce-row { grid-template-columns: 1fr; }
        .ce-card-header { padding: 1rem 1.25rem; }
        .ce-card-body { padding: 1.25rem; }
        .ce-card-footer { padding: 1rem 1.25rem; flex-wrap: wrap; }
        .ce-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="center-edit-shell">

    {{-- ── Page header ── --}}
    <div class="ce-header">
        <a class="ce-back" href="{{ route('centers.index') }}">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M10 12L6 8l4-4"/>
            </svg>
            Retour aux centres
        </a>
        <div class="ce-page-title">Modifier le centre</div>
        <div class="ce-page-sub">Mettre à jour les informations de <strong>{{ $center->name }}</strong></div>
    </div>

    {{-- ── Validation errors ── --}}
    @if ($errors->any())
        <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:13px;color:#B91C1C;">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul style="margin:.5rem 0 0 1rem;padding:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── Main card ── --}}
    <div class="ce-card">

        {{-- Card header --}}
        <div class="ce-card-header">
            <div class="ce-card-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="4" width="14" height="13" rx="2"/>
                    <path d="M7 4V2.5M13 4V2.5M3 8h14"/>
                </svg>
            </div>
            <div>
                <div class="ce-card-title">Informations du centre</div>
                <div class="ce-card-note">Les champs marqués d'un <span style="color:#EF4444">*</span> sont obligatoires</div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('centers.update', $center) }}" data-loading="true" data-loading-text="Mise a jour du centre en cours...">
            @csrf
            @method('PUT')

            <div class="ce-card-body">

                {{-- Section : Identification --}}
                <div class="ce-section">
                    <div class="ce-section-label">Identification</div>
                    <div class="ce-row">
                        <div class="ce-field">
                            <label class="ce-label" for="name">
                                Nom du centre <span class="req">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ safeOld('name', $center->name) }}"
                                class="ce-input {{ $errors->has('name') ? 'is-error' : '' }}"
                                placeholder="Ex: Centre Médical Akwa"
                                required
                            >
                            @error('name')
                                <span class="ce-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ce-field">
                            <label class="ce-label" for="code">
                                Code du centre <span class="req">*</span>
                            </label>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ safeOld('code', $center->code) }}"
                                class="ce-input {{ $errors->has('code') ? 'is-error' : '' }}"
                                placeholder="Ex: CTR-001"
                                required
                            >
                            @error('code')
                                <span class="ce-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="ce-divider"></div>

                {{-- Section : Localisation --}}
                <div class="ce-section">
                    <div class="ce-section-label">Localisation</div>
                    <div class="ce-row">
                        <div class="ce-field">
                            <label class="ce-label" for="city">
                                Ville <span class="req">*</span>
                            </label>
                            <input
                                type="text"
                                id="city"
                                name="city"
                                value="{{ safeOld('city', $center->city) }}"
                                class="ce-input {{ $errors->has('city') ? 'is-error' : '' }}"
                                placeholder="Ex: Douala"
                                required
                            >
                            @error('city')
                                <span class="ce-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ce-field">
                            <label class="ce-label" for="country">
                                Pays <span class="req">*</span>
                            </label>
                            <input
                                type="text"
                                id="country"
                                name="country"
                                value="{{ safeOld('country', $center->country) }}"
                                class="ce-input {{ $errors->has('country') ? 'is-error' : '' }}"
                                placeholder="Ex: Cameroun"
                                required
                            >
                            @error('country')
                                <span class="ce-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="ce-divider"></div>

                {{-- Section : Contact --}}
                <div class="ce-section">
                    <div class="ce-section-label">Contact</div>
                    <div class="ce-row">
                        <div class="ce-field">
                            <label class="ce-label" for="email">E-mail</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ safeOld('email', $center->email) }}"
                                class="ce-input {{ $errors->has('email') ? 'is-error' : '' }}"
                                placeholder="contact@centre.cm"
                            >
                            @error('email')
                                <span class="ce-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ce-field">
                            <label class="ce-label" for="phone">Téléphone</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                value="{{ safeOld('phone', $center->phone) }}"
                                class="ce-input {{ $errors->has('phone') ? 'is-error' : '' }}"
                                placeholder="+237 6XX XXX XXX"
                            >
                            @error('phone')
                                <span class="ce-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="ce-row full">
                        <div class="ce-field">
                            <label class="ce-label" for="address">Adresse complète</label>
                            <textarea
                                id="address"
                                name="address"
                                class="ce-textarea {{ $errors->has('address') ? 'is-error' : '' }}"
                                placeholder="Rue, quartier, ville..."
                            >{{ safeOld('address', $center->address) }}</textarea>
                            @error('address')
                                <span class="ce-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="ce-divider"></div>

                {{-- Section : Statut --}}
                <div class="ce-section" style="margin-bottom:0">
                    <div class="ce-section-label">Statut</div>
                    <div class="ce-row">
                        <div class="ce-field">
                            <label class="ce-label" for="is_active">État du centre</label>
                            <div class="ce-select-wrap">
                                <select
                                    id="is_active"
                                    name="is_active"
                                    class="ce-select"
                                    onchange="updateStatus(this)"
                                >
                                    <option value="1" {{ safeOld('is_active', $center->is_active) ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ !safeOld('is_active', $center->is_active) ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                        </div>
                        <div class="ce-status-wrap" style="justify-content:flex-end;padding-bottom:2px">
                            <label class="ce-label" style="visibility:hidden">état</label>
                            <div id="ce-status-badge" class="ce-status-badge {{ $center->is_active ? 'active' : 'inactive' }}">
                                <span class="ce-dot {{ $center->is_active ? 'active' : 'inactive' }}" id="ce-dot"></span>
                                <span id="ce-badge-text">
                                    {{ $center->is_active ? 'Centre actuellement actif' : 'Centre actuellement inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /card-body --}}

            {{-- Card footer --}}
            <div class="ce-card-footer">
                <a href="{{ route('centers.index') }}" class="ce-btn">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M10 12L6 8l4-4"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="ce-btn ce-btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M13 4.5L6.5 11 3 7.5"/>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>

        </form>
    </div>{{-- /card --}}

</div>{{-- /shell --}}

<script>
function updateStatus(select) {
    var badge   = document.getElementById('ce-status-badge');
    var dot     = document.getElementById('ce-dot');
    var text    = document.getElementById('ce-badge-text');
    var isActive = select.value === '1';

    badge.className = 'ce-status-badge ' + (isActive ? 'active' : 'inactive');
    dot.className   = 'ce-dot '          + (isActive ? 'active' : 'inactive');
    text.textContent = isActive ? 'Centre actuellement actif' : 'Centre actuellement inactif';
}
</script>

@endsection




