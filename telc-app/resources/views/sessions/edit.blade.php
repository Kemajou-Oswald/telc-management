@extends('layouts.app')

@section('page_title', 'Modifier une session')
@section('page_kicker', 'Examens')
@section('page_subtitle', 'Modifiez les informations de la session.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('sessions.index') }}">Retour</a>
@endsection

@section('content')

<style>
    .se-shell * { box-sizing: border-box; }

    .se-shell {
        max-width: 820px;
        margin: 0 auto;
        padding: 0 0 3rem;
    }

    /* ── Card ── */
    .se-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 16px;
        overflow: hidden;
        animation: se-fade-up .4s ease both;
        box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.04);
    }

    .se-card-header {
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fafafa;
    }

    .se-card-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #FFF7ED;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .se-card-icon svg { width: 20px; height: 20px; color: #EA580C; }

    .se-card-title { font-size: 15px; font-weight: 600; color: #111; }
    .se-card-note  { font-size: 12px; color: #999; margin-top: 2px; }

    /* ── Body ── */
    .se-card-body { padding: 1.75rem; }

    /* ── Section label ── */
    .se-section { margin-bottom: 1.5rem; }

    .se-section-label {
        font-size: 10.5px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .1em;
        color: #aaa; margin-bottom: 1rem;
        display: flex; align-items: center; gap: 8px;
    }

    .se-section-label::after {
        content: ''; flex: 1; height: 1px; background: #f0f0f0;
    }

    /* ── Grid ── */
    .se-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .se-row.full { grid-template-columns: 1fr; }

    .se-field { display: flex; flex-direction: column; gap: 6px; }

    /* ── Labels ── */
    .se-label {
        font-size: 13px; font-weight: 500; color: #555;
        display: flex; align-items: center; gap: 4px;
    }

    .se-label .req { color: #EF4444; font-size: 14px; line-height: 1; }

    /* ── Inputs ── */
    .se-input,
    .se-select,
    .se-textarea {
        font-family: inherit; font-size: 14px;
        color: #111; background: #fff;
        border: 1px solid #e2e2e2;
        border-radius: 10px;
        padding: 9px 13px;
        width: 100%; outline: none;
        transition: border-color .15s, box-shadow .15s;
        -webkit-appearance: none; appearance: none;
    }

    .se-input:hover, .se-select:hover, .se-textarea:hover { border-color: #ccc; }

    .se-input:focus, .se-select:focus, .se-textarea:focus {
        border-color: #EA580C;
        box-shadow: 0 0 0 3px rgba(234,88,12,.1);
    }

    .se-textarea { resize: vertical; min-height: 96px; line-height: 1.6; }

    /* Custom select arrow */
    .se-select-wrap { position: relative; }

    .se-select-wrap::after {
        content: ''; position: absolute;
        right: 12px; top: 50%; transform: translateY(-50%);
        width: 0; height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #888;
        pointer-events: none;
    }

    .se-select { padding-right: 32px; cursor: pointer; }

    /* ── Status badge ── */
    .se-status-badge {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 6px 12px; border-radius: 99px;
        font-size: 12.5px; font-weight: 500;
        transition: background .2s, color .2s;
    }

    .se-status-badge.draft   { background: #F4F4F5; color: #71717A; }
    .se-status-badge.open    { background: #F0FDF4; color: #15803D; }
    .se-status-badge.closed  { background: #FEF2F2; color: #991B1B; }

    .se-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; transition: background .2s; }
    .se-dot.draft  { background: #A1A1AA; }
    .se-dot.open   { background: #22C55E; }
    .se-dot.closed { background: #EF4444; }

    /* ── Divider ── */
    .se-divider { height: 1px; background: #f4f4f4; margin: 1.5rem 0; }

    /* ── Error ── */
    .se-error { font-size: 12px; color: #EF4444; margin-top: 2px; }
    .se-input.is-error, .se-select.is-error, .se-textarea.is-error { border-color: #FCA5A5; }

    /* ── Footer ── */
    .se-card-footer {
        display: flex; justify-content: flex-end; align-items: center; gap: 10px;
        padding: 1.25rem 1.75rem;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .se-btn {
        font-family: inherit; font-size: 14px; font-weight: 500;
        padding: 9px 20px; border-radius: 10px; cursor: pointer;
        border: 1px solid #e2e2e2; background: #fff; color: #444;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 7px;
        transition: background .15s, border-color .15s, transform .1s;
    }

    .se-btn:hover { background: #f6f6f6; border-color: #ccc; }
    .se-btn:active { transform: scale(.98); }

    .se-btn svg { width: 15px; height: 15px; flex-shrink: 0; }

    .se-btn-primary { background: #111; color: #fff; border-color: #111; }
    .se-btn-primary:hover { background: #222; border-color: #222; }

    /* ── Animation ── */
    @keyframes se-fade-up {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Responsive ── */
    @media (max-width: 600px) {
        .se-row { grid-template-columns: 1fr; }
        .se-card-header { padding: 1rem 1.25rem; }
        .se-card-body   { padding: 1.25rem; }
        .se-card-footer { padding: 1rem 1.25rem; flex-wrap: wrap; }
        .se-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="se-shell">

    {{-- Validation errors --}}
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

    <div class="se-card">

        {{-- Header --}}
        <div class="se-card-header">
            <div class="se-card-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="4" width="14" height="13" rx="2"/>
                    <path d="M7 4V2.5M13 4V2.5M3 8h14"/>
                    <path d="M7 12h2M7 14.5h6"/>
                </svg>
            </div>
            <div>
                <div class="se-card-title">Informations de la session</div>
                <div class="se-card-note">Les champs marqués d'un <span style="color:#EF4444">*</span> sont obligatoires</div>
            </div>
        </div>

        {{-- Form --}}
        @php
    $selectedCenterIds = old('center_ids', $session->centers->pluck('id')->toArray());
    $initialCities = $centerOptions->whereIn('id', $selectedCenterIds)->pluck('city')->filter()->unique()->values();
    $oldCity = old('city', $session->city);
    if ($oldCity && ! $initialCities->contains($oldCity)) {
        $initialCities = $initialCities->prepend($oldCity)->unique();
    }
@endphp
<form method="POST" action="{{ route('sessions.update', $session) }}" data-loading="true" data-loading-text="Mise a jour en cours...">
            @csrf
            @method('PUT')

            <div class="se-card-body">

                {{-- Identification --}}
                <div class="se-section">
                    <div class="se-section-label">Identification</div>
                    <div class="se-row">
                        <div class="se-field">
                            <label class="se-label" for="title">
                                Titre de la session <span class="req">*</span>
                            </label>
                            <input
                                type="text" id="title" name="title"
                                value="{{ old('title', $session->title) }}"
                                class="se-input {{ $errors->has('title') ? 'is-error' : '' }}"
                                placeholder="Ex: Session TELC B2 — Juin 2025"
                                required>
                            @error('title')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="se-field">
                            <label class="se-label" for="center_ids">
                                Centres <span class="req">*</span>
                            </label>
                            <div class="se-select-wrap" style="position:relative;">
                                <select id="center_ids" name="center_ids[]" class="se-select {{ $errors->has('center_ids') ? 'is-error' : '' }}" multiple required style="min-height:80px;">
                                    @foreach ($centerOptions as $center)
                                        <option value="{{ $center->id }}" data-city="{{ $center->city }}" {{ old('center_ids', $session->centers->pluck('id')->toArray()) && in_array($center->id, old('center_ids', $session->centers->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $center->name }}{{ $center->city ? ' – ' . $center->city : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small style="color:#888;font-size:11px;margin-top:4px;display:block;">
                                    Maintenez Ctrl (ou Cmd) enfoncé pour sélectionner plusieurs centres
                                </small>
                            </div>
                            @error('center_ids')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="se-row">
                        <div class="se-field">
                            <label class="se-label" for="city">
                                Ville de la session <span class="req">*</span>
                            </label>
                            <div class="se-select-wrap">
                                <select id="city" name="city" class="se-select {{ $errors->has('city') ? 'is-error' : '' }}" required>
                                    @if ($initialCities->isEmpty())
                                        <option value="">Selectionner d'abord les centres</option>
                                    @else
                                        @foreach ($initialCities as $city)
                                            <option value="{{ $city }}" {{ old('city', $session->city) === $city ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('city')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="se-field"></div>
                    </div>
                </div>

                <div class="se-divider"></div>

                {{-- Niveau & Prix --}}
                <div class="se-section">
                    <div class="se-section-label">Examen</div>
                    <div class="se-row">
                        <div class="se-field">
                            <label class="se-label" for="level">
                                Niveau <span class="req">*</span>
                            </label>
                            <div class="se-select-wrap">
                                <select id="level" name="level" class="se-select {{ $errors->has('level') ? 'is-error' : '' }}" required>
                                    @foreach (['A1','A2','B1','B2','C1','C2'] as $lvl)
                                        <option value="{{ $lvl }}" {{ old('level', $session->level) == $lvl ? 'selected' : '' }}>
                                            {{ $lvl }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('level')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="se-field">
                            <label class="se-label" for="price">
                                Prix (FCFA) <span class="req">*</span>
                            </label>
                            <input
                                type="number" id="price" name="price"
                                value="{{ old('price', $session->price) }}"
                                step="0.01" min="0"
                                class="se-input {{ $errors->has('price') ? 'is-error' : '' }}"
                                placeholder="Ex: 75000"
                                required>
                            @error('price')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="se-divider"></div>

                {{-- Dates --}}
                <div class="se-section">
                    <div class="se-section-label">Dates</div>
                    <div class="se-row">
                        <div class="se-field">
                            <label class="se-label" for="opens_at">
                                Ouverture des inscriptions <span class="req">*</span>
                            </label>
                            <input
                                type="datetime-local" id="opens_at" name="opens_at"
                                value="{{ old('opens_at', $session->opens_at ? $session->opens_at->format('Y-m-d\TH:i') : '') }}"
                                class="se-input {{ $errors->has('opens_at') ? 'is-error' : '' }}"
                                required>
                            @error('opens_at')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="se-field">
                            <label class="se-label" for="closes_at">Fermeture des inscriptions</label>
                            <input
                                type="datetime-local" id="closes_at" name="closes_at"
                                value="{{ old('closes_at', $session->closes_at ? $session->closes_at->format('Y-m-d\TH:i') : '') }}"
                                class="se-input {{ $errors->has('closes_at') ? 'is-error' : '' }}">
                            @error('closes_at')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="se-row">
                        <div class="se-field">
                            <label class="se-label" for="exam_date">Date de l'examen</label>
                            <input
                                type="date" id="exam_date" name="exam_date"
                                value="{{ old('exam_date', $session->exam_date ? $session->exam_date->format('Y-m-d') : '') }}"
                                class="se-input {{ $errors->has('exam_date') ? 'is-error' : '' }}">
                            @error('exam_date')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="se-field"></div>
                    </div>
                </div>

                <div class="se-divider"></div>

                {{-- Statut --}}
                <div class="se-section">
                    <div class="se-section-label">Statut</div>
                    <div class="se-row">
                        <div class="se-field">
                            <label class="se-label" for="status">
                                État de la session <span class="req">*</span>
                            </label>
                            <div class="se-select-wrap">
                                <select id="status" name="status" class="se-select {{ $errors->has('status') ? 'is-error' : '' }}"
                                    onchange="updateStatusBadge(this)" required>
                                    <option value="draft"  {{ old('status', $session->status) == 'draft'  ? 'selected' : '' }}>Brouillon</option>
                                    <option value="open"   {{ old('status', $session->status) == 'open'   ? 'selected' : '' }}>Ouverte</option>
                                    <option value="closed" {{ old('status', $session->status) == 'closed' ? 'selected' : '' }}>Fermée</option>
                                </select>
                            </div>
                            @error('status')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;justify-content:flex-end;padding-bottom:2px">
                            <label class="se-label" style="visibility:hidden">état</label>
                            <div id="se-status-badge" class="se-status-badge {{ old('status', $session->status) }}">
                                <span class="se-dot {{ old('status', $session->status) }}" id="se-dot"></span>
                                <span id="se-badge-text">
                                    @php
                                        $statusLabels = ['draft' => 'Brouillon', 'open' => 'Session ouverte', 'closed' => 'Session fermée'];
                                    @endphp
                                    {{ $statusLabels[old('status', $session->status)] ?? '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="se-divider"></div>

                {{-- Notes --}}
                <div class="se-section" style="margin-bottom:0">
                    <div class="se-section-label">Notes</div>
                    <div class="se-row full">
                        <div class="se-field">
                            <label class="se-label" for="notes">Notes internes</label>
                            <textarea id="notes" name="notes"
                                class="se-textarea {{ $errors->has('notes') ? 'is-error' : '' }}"
                                placeholder="Informations complémentaires sur la session...">{{ old('notes', $session->notes) }}</textarea>
                            @error('notes')<span class="se-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

            </div>{{-- /card-body --}}

            <div class="se-card-footer">
                <a href="{{ route('sessions.index') }}" class="se-btn">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M10 12L6 8l4-4"/>
                    </svg>
                    Annuler
                </a>
                <button type="submit" class="se-btn se-btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M13 4.5L6.5 11 3 7.5"/>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    var statusLabels = { draft: 'Brouillon', open: 'Session ouverte', closed: 'Session fermée' };

    function updateStatusBadge(select) {
        var val   = select.value;
        var badge = document.getElementById('se-status-badge');
        var dot   = document.getElementById('se-dot');
        var text  = document.getElementById('se-badge-text');

        badge.className = 'se-status-badge ' + val;
        dot.className   = 'se-dot ' + val;
        text.textContent = statusLabels[val] || '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var centerSelect = document.getElementById('center_ids');
        var citySelect = document.getElementById('city');
        if (!centerSelect || !citySelect) return;

        var buildCityOptions = function() {
            var selectedCities = Array.from(centerSelect.selectedOptions)
                .map(function(option) { return option.dataset.city; })
                .filter(function(city) { return city && city.trim().length > 0; });
            var uniqueCities = Array.from(new Set(selectedCities));
            var previousValue = citySelect.value;

            citySelect.innerHTML = '';

            if (uniqueCities.length === 0) {
                var emptyOption = new Option('Selectionner d\'abord les centres', '');
                citySelect.appendChild(emptyOption);
                return;
            }

            uniqueCities.forEach(function(city) {
                var option = new Option(city, city);
                if (city === previousValue) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });

            if (!citySelect.value && uniqueCities.length > 0) {
                citySelect.selectedIndex = 0;
            }
        };

        buildCityOptions();
        centerSelect.addEventListener('change', buildCityOptions);
    });
</script>

@endsection

