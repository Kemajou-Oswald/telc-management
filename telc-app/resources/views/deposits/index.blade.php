@extends('layouts.app')

@section('page_title', 'Depots bancaires')
@section('page_kicker', 'Operations financieres')
@section('page_subtitle', 'Suivez les versements, bordereaux et validations par centre et periode.')

@push('styles')
<style>
/* ══════════════════════════════════════
   AVATAR FIX — images contraintes
   ══════════════════════════════════════ */
.user-line {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.user-line .user-avatar-chip,
.treatment-validator .user-avatar-chip {
    flex-shrink: 0;
    overflow: hidden;
    border-radius: 6px;
    display: grid;
    place-items: center;
    font-size: 8px;
    font-weight: 700;
    color: #fff;
    background: #6366f1;
    line-height: 1;
    position: relative;
}

.user-line .user-avatar-chip {
    width: 28px;
    height: 28px;
    min-width: 28px;
    min-height: 28px;
    max-width: 28px;
    max-height: 28px;
}

.treatment-validator .user-avatar-chip {
    width: 26px;
    height: 26px;
    min-width: 26px;
    min-height: 26px;
    max-width: 26px;
    max-height: 26px;
}

.user-line .user-avatar-chip img,
.user-line .user-avatar-chip .user-avatar-img,
.treatment-validator .user-avatar-chip img,
.treatment-validator .user-avatar-chip .user-avatar-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: inherit;
}

.user-line > img {
    flex-shrink: 0;
    object-fit: cover;
    display: block;
    border-radius: 6px;
    width: 28px;
    height: 28px;
    max-width: 28px;
    max-height: 28px;
}

/* ══════════════════════════════════════
   HERO HEADER DE PAGE
   ══════════════════════════════════════ */
.dep-hero {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
    border-radius: 16px;
    padding: 36px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    position: relative;
    overflow: hidden;
    margin-bottom: 4px;
}

.dep-hero::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
}

.dep-hero::after {
    content: '';
    position: absolute;
    bottom: -80px;
    right: 120px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
    pointer-events: none;
}

.dep-hero-left { position: relative; z-index: 1; }

.dep-hero-kicker {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #c7d2fe;
    margin-bottom: 14px;
}

.dep-hero-title {
    font-size: 28px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.02em;
    line-height: 1.1;
    margin-bottom: 8px;
}

.dep-hero-sub {
    font-size: 14px;
    color: #a5b4fc;
    line-height: 1.5;
}

.dep-hero-stats {
    display: flex;
    gap: 12px;
    position: relative;
    z-index: 1;
    flex-shrink: 0;
}

.dep-stat-card {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 16px 20px;
    text-align: center;
    min-width: 90px;
}

.dep-stat-value {
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    margin-bottom: 4px;
}

.dep-stat-label {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #a5b4fc;
}

.dep-stat-card.stat-pending .dep-stat-value { color: #fde68a; }
.dep-stat-card.stat-validated .dep-stat-value { color: #6ee7b7; }

/* ══════════════════════════════════════
   SECTION FILTRES
   ══════════════════════════════════════ */
.dep-filter-section {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 4px;
}

.dep-filter-header {
    padding: 16px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dep-filter-header-icon {
    width: 30px;
    height: 30px;
    background: #f1f5f9;
    border-radius: 8px;
    display: grid;
    place-items: center;
    color: #64748b;
}

.dep-filter-title {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
}

.dep-filter-body {
    padding: 20px 24px;
}

/* ══════════════════════════════════════
   TABLE
   ══════════════════════════════════════ */
.deposits-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
}

.deposits-table th {
    background: #f8fafc;
    padding: 13px 18px;
    text-align: left;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #94a3b8;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}

.deposits-table td {
    padding: 16px 18px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
    vertical-align: middle;
    color: #374151;
}

.deposits-table tbody tr:hover td { background: #fafbff; }
.deposits-table tbody tr:last-child td { border-bottom: none; }

/* ══════════════════════════════════════
   TABLE CARD WRAPPER
   ══════════════════════════════════════ */
.dep-table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
}

.dep-table-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dep-table-card-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
}

.dep-table-card-sub {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 2px;
}

/* ══════════════════════════════════════
   CELLULES
   ══════════════════════════════════════ */
.cell-center-name {
    font-weight: 600;
    color: #0f172a;
    font-size: 13px;
    margin-bottom: 2px;
}

.cell-center-city {
    font-size: 11px;
    color: #94a3b8;
}

.cell-amount {
    font-weight: 700;
    font-size: 14px;
    color: #0f172a;
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

.cell-date {
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

.cell-session {
    font-size: 12px;
    color: #475569;
    line-height: 1.4;
}

/* ══════════════════════════════════════
   STATUTS
   ══════════════════════════════════════ */
.dep-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    white-space: nowrap;
}

.dep-badge-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}

.dep-badge--validated { background: #dcfce7; color: #15803d; }
.dep-badge--validated .dep-badge-dot { background: #16a34a; }

.dep-badge--pending { background: #fef3c7; color: #b45309; }
.dep-badge--pending .dep-badge-dot { background: #d97706; }

.dep-badge--rejected { background: #fef2f2; color: #dc2626; }
.dep-badge--rejected .dep-badge-dot { background: #ef4444; }

/* ══════════════════════════════════════
   BOUTONS BORDEREAU
   ══════════════════════════════════════ */
.dep-btn-group {
    display: flex;
    gap: 6px;
    align-items: center;
}

.dep-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #475569;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    white-space: nowrap;
}

.dep-btn:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
}

/* ══════════════════════════════════════
   FORMULAIRE TRAITEMENT
   ══════════════════════════════════════ */
.review-form {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.review-select {
    padding: 7px 10px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 500;
    background: #fff;
    color: #374151;
    cursor: pointer;
}

.review-select:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}

.review-textarea {
    padding: 8px 10px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
    font-size: 12px;
    resize: vertical;
    min-height: 56px;
    font-family: inherit;
    color: #374151;
}

.review-textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}

.btn-traiter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px 14px;
    font-size: 12px;
    font-weight: 700;
    border-radius: 7px;
    border: none;
    background: #4f46e5;
    color: #fff;
    cursor: pointer;
    width: 100%;
    letter-spacing: 0.02em;
}

.btn-traiter:hover { background: #4338ca; }

/* ══════════════════════════════════════
   TRAITEMENT INFO
   ══════════════════════════════════════ */
.treatment-validator {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}

.validator-name {
    font-weight: 600;
    color: #0f172a;
    font-size: 12px;
}

.validator-date {
    font-size: 10px;
    color: #94a3b8;
}

.treatment-note {
    margin-top: 6px;
    padding: 7px 10px;
    background: #fffbeb;
    border-left: 3px solid #f59e0b;
    border-radius: 0 6px 6px 0;
    font-size: 11px;
    color: #78350f;
    line-height: 1.5;
}

.treatment-empty {
    color: #cbd5e1;
    font-size: 12px;
    font-style: italic;
    text-align: center;
    padding: 8px 0;
}

/* ══════════════════════════════════════
   ETAT VIDE
   ══════════════════════════════════════ */
.dep-empty {
    padding: 56px 24px;
    text-align: center;
}

.dep-empty-icon {
    width: 52px;
    height: 52px;
    background: #f1f5f9;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    color: #94a3b8;
}

.dep-empty-title {
    font-size: 15px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 5px;
}

.dep-empty-desc {
    font-size: 13px;
    color: #94a3b8;
}
</style>
@endpush

@section('page_actions')
    @if (! $user->isSuperAdmin())
        <a class="btn btn-primary" href="{{ route('deposits.create', request()->only('center_id')) }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:6px">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nouveau depot
        </a>
    @endif
@endsection

@section('content')
<div class="page-shell stack-grid theme-page theme-coral">


    {{-- ══ FILTRES ══ --}}
    <div class="dep-filter-section">
        <div class="dep-filter-header">
            <div class="dep-filter-header-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
            </div>
            <div class="dep-filter-title">Recherche et filtres</div>
        </div>
        <div class="dep-filter-body">
            <form method="GET">
                <div class="toolbar-grid">
                    <div class="search-wrap">
                        <span class="search-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>
                        <input class="search-input" type="text" name="q" value="{{ request('q') }}" placeholder="Centre, deposant, session, montant...">
                    </div>
                    @if ($centerOptions->isNotEmpty())
                        <div class="field">
                            <label>Centre</label>
                            <select name="center_id">
                                <option value="">Tous les centres</option>
                                @foreach ($centerOptions as $center)
                                    <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="field">
                        <label>Deposant</label>
                        <select name="submitted_by">
                            <option value="">Tous</option>
                            @foreach ($submitterOptions as $submitter)
                                <option value="{{ $submitter->id }}" {{ request('submitted_by') == $submitter->id ? 'selected' : '' }}>{{ $submitter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Session</label>
                        <select name="session_id">
                            <option value="">Toutes</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>{{ $session->title }} - {{ optional($session->center)->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Statut</label>
                        <select name="status">
                            <option value="">Tous</option>
                            <option value="pending"   {{ request('status') === 'pending'    ? 'selected' : '' }}>En attente</option>
                            <option value="validated" {{ request('status') === 'validated'  ? 'selected' : '' }}>Valide</option>
                            <option value="rejected"  {{ request('status') === 'rejected'   ? 'selected' : '' }}>Refuse</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Date debut</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="field">
                        <label>Date fin</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="toolbar-actions" style="margin-top:16px">
                    @if (request()->hasAny(['q', 'center_id', 'submitted_by', 'session_id', 'status', 'date_from', 'date_to']))
                        <a class="btn btn-ghost" href="{{ route('deposits.index') }}">Réinitialiser</a>
                    @endif
                    <button class="btn btn-secondary" type="submit">Appliquer les filtres</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ TABLE DÉPÔTS ══ --}}
    <div class="dep-table-card">
        <div class="dep-table-card-header">
            <div>
                <div class="dep-table-card-title">Liste des depots</div>
                <div class="dep-table-card-sub">Consultez les versements, telechargez les bordereaux et suivez les validations.</div>
            </div>
        </div>
        <div class="table-wrap">
            <table class="deposits-table">
                <thead>
                    <tr>
                        <th style="width:190px">Centre</th>
                        <th style="width:170px">Deposant</th>
                        <th>Session</th>
                        <th style="width:130px">Montant</th>
                        <th style="width:105px">Date</th>
                        <th style="width:110px">Statut</th>
                        <th style="width:155px">Bordereau</th>
                        <th style="width:220px">Traitement</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deposits as $deposit)
                        @php
                            $statusLabel = $deposit->status === 'validated' ? 'Validé' : ($deposit->status === 'rejected' ? 'Refuse' : 'En attente');
                        @endphp
                        <tr>
                            {{-- Centre --}}
                            <td>
                                <div class="cell-center-name">{{ optional($deposit->center)->name ?: 'Centre inconnu' }}</div>
                                @if (optional($deposit->center)->city)
                                    <div class="cell-center-city">{{ $deposit->center->city }}</div>
                                @endif
                            </td>

                            {{-- Déposant --}}
                            <td>
                                <div class="user-line">
                                    @include('partials.user-avatar', ['user' => $deposit->submitter, 'size' => 28])
                                    <div style="font-weight:500;color:#0f172a;font-size:13px">
                                        {{ optional($deposit->submitter)->name ?: 'Système' }}
                                    </div>
                                </div>
                            </td>

                            {{-- Session --}}
                            <td>
                                <div class="cell-session">{{ optional($deposit->examSession)->title ?: '—' }}</div>
                            </td>

                            {{-- Montant --}}
                            <td>
                                <div class="cell-amount">{{ number_format($deposit->amount, 0, ',', ' ') }} FCFA</div>
                            </td>

                            {{-- Date --}}
                            <td>
                                <div class="cell-date">{{ optional($deposit->deposit_date)->format('d/m/Y') }}</div>
                            </td>

                            {{-- Statut --}}
                            <td>
                                <span class="dep-badge dep-badge--{{ $deposit->status }}">
                                    <span class="dep-badge-dot"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Bordereau --}}
                            <td>
                                <div class="dep-btn-group">
                                    <a class="dep-btn" href="{{ route('deposits.slip.preview', $deposit) }}">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Voir
                                    </a>
                                    <a class="dep-btn" href="{{ route('deposits.slip.download', $deposit) }}">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        DL
                                    </a>
                                </div>
                            </td>

                            {{-- Traitement --}}
                            <td style="min-width:200px">
                                @if ($user->isSuperAdmin() && $deposit->status === 'pending')
                                    <form method="POST" action="{{ route('deposits.review', $deposit) }}" class="review-form" id="review_form_{{ $deposit->id }}" data-loading="true" data-loading-text="Traitement du depot en cours...">
                                        @csrf
                                        <select name="status" class="review-select" onchange="toggleNoteRequired(this, {{ $deposit->id }})">
                                            <option value="validated">Valider</option>
                                            <option value="rejected">Refuser</option>
                                        </select>
                                        <textarea name="admin_note" class="review-textarea" id="admin_note_{{ $deposit->id }}" placeholder="Commentaire (obligatoire si refus)..."></textarea>
                                        <div id="error_alert_{{ $deposit->id }}" style="display:none; margin:8px 0; padding:10px 12px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; font-size:12px; color:#dc2626;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:6px;">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="12" y1="8" x2="12" y2="12"/>
                                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                                            </svg>
                                            <span id="error_msg_{{ $deposit->id }}">Le commentaire est obligatoire lors d'un refus.</span>
                                        </div>
                                        <button class="btn-traiter" type="button" onclick="submitReview({{ $deposit->id }})">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                            Traiter
                                        </button>
                                    </form>
                                    <script>
                                        function toggleNoteRequired(select, depositId) {
                                            const textarea = document.getElementById('admin_note_' + depositId);
                                            const errorDiv = document.getElementById('error_alert_' + depositId);
                                            if (select.value === 'rejected') {
                                                textarea.placeholder = 'Commentaire obligatoire (min. 3 caractères)...';
                                                textarea.style.borderColor = '#f59e0b';
                                            } else {
                                                textarea.placeholder = 'Commentaire optionnel...';
                                                textarea.style.borderColor = '#d1d5db';
                                            }
                                            errorDiv.style.display = 'none';
                                        }
                                        function submitReview(depositId) {
                                            const form = document.getElementById('review_form_' + depositId);
                                            const status = form.querySelector('select[name="status"]').value;
                                            const note = form.querySelector('textarea[name="admin_note"]').value.trim();
                                            const errorDiv = document.getElementById('error_alert_' + depositId);
                                            const errorMsg = document.getElementById('error_msg_' + depositId);
                                            
                                            if (status === 'rejected' && note.length < 3) {
                                                errorMsg.textContent = 'Le commentaire est obligatoire lors d\'un refus (minimum 3 caractères).';
                                                errorDiv.style.display = 'block';
                                                form.querySelector('textarea[name="admin_note"]').focus();
                                                return false;
                                            }
                                            
                                            errorDiv.style.display = 'none';
                                            if (typeof showGlobalLoader === 'function') {
                                                showGlobalLoader('Traitement du depot en cours...');
                                            }
                                            const btn = form.querySelector('.btn-traiter');
                                            if (btn) {
                                                btn.disabled = true;
                                            }
                                            form.submit();
                                        }
                                    </script>
                                @else
                                    @if ($deposit->validated_at)
                                        <div class="treatment-validator">
                                            @include('partials.user-avatar', ['user' => $deposit->validator, 'size' => 26])
                                            <div>
                                                <div class="validator-name">{{ optional($deposit->validator)->name ?: 'Système' }}</div>
                                                <div class="validator-date">{{ optional($deposit->validated_at)->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="treatment-empty">Non traité</div>
                                    @endif
                                    @if ($deposit->admin_note)
                                        <div class="treatment-note"><strong>Note :</strong> {{ $deposit->admin_note }}</div>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="dep-empty">
                                    <div class="dep-empty-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="2" y="5" width="20" height="14" rx="3"/>
                                            <line x1="2" y1="10" x2="22" y2="10"/>
                                        </svg>
                                    </div>
                                    <div class="dep-empty-title">Aucun depot enregistre</div>
                                    <div class="dep-empty-desc">Les depots bancaires apparaitront ici apres enregistrement.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($deposits->hasPages())
            <div class="pagination" style="padding:16px 24px">{{ $deposits->links() }}</div>
        @endif
    </div>

    {{-- ══ HISTORIQUE ══ --}}
    @if ($depositAudits->isNotEmpty())
        <div class="dep-table-card">
            <div class="dep-table-card-header">
                <div>
                    <div class="dep-table-card-title">Historique récent</div>
                    <div class="dep-table-card-sub">Les soumissions et validations récentes apparaissent ici.</div>
                </div>
            </div>
            <div class="table-wrap">
                <table class="deposits-table">
                    <thead>
                        <tr>
                            <th>Depot / Centre</th>
                            <th style="width:130px">Action</th>
                            <th style="width:180px">Par</th>
                            <th style="width:140px">Date</th>
                            <th>Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($depositAudits as $audit)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#0f172a;font-size:13px">
                                        {{ optional(optional($audit->deposit)->center)->name ?: 'Depot' }}
                                    </div>
                                </td>
                                <td><span class="badge neutral">{{ $audit->action }}</span></td>
                                <td>
                                    <div class="user-line">
                                        @include('partials.user-avatar', ['user' => $audit->user, 'size' => 26])
                                        <span style="font-size:12px;color:#374151;font-weight:500">
                                            {{ optional($audit->user)->name ?: 'Système' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="cell-date">{{ optional($audit->created_at)->format('d/m/Y H:i') }}</td>
                                <td style="font-size:12px;color:#64748b;line-height:1.5">{{ $audit->details }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
