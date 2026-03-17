@extends('layouts.app')

@section('page_title', 'Rapports et export')
@section('page_kicker', 'Analyse')
@section('page_subtitle', 'G&#233;n&#233;rez un export par session, niveau, centre ou statut de paiement.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('registrations.index') }}">Retour aux inscriptions</a>
    <a class="btn btn-secondary" href="{{ route('registrations.export.pdf', request()->query()) }}" data-loading="true" data-loading-text="Generation du PDF en cours...">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Exporter en PDF
    </a>
    <a class="btn btn-primary" href="{{ route('registrations.export.download', request()->query()) }}" data-loading="true" data-loading-text="Export XLSX en cours...">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Exporter en XLSX
    </a>
@endsection

@push('styles')
<style>
/* ── Session picker ── */
.rex-picker {
    display: flex; flex-direction: column; gap: 14px;
    padding: 18px;
    border: 1px solid rgba(216,227,244,0.9);
    border-radius: 18px;
    background: linear-gradient(180deg, #ffffff, #fbfcff);
}
.rex-picker__head {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; flex-wrap: wrap;
}
.rex-picker__title { font-size: 13px; font-weight: 700; color: var(--navy); }
.rex-picker__text  { font-size: 12px; color: #72809f; margin-top: 3px; }
.rex-picker__tools { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.rex-picker__count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 34px; height: 28px; padding: 0 10px;
    border-radius: 999px; background: rgba(26,26,26,0.08);
    color: var(--navy); font-size: 12px; font-weight: 700;
}
.rex-picker__btn {
    height: 28px; padding: 0 12px;
    border: 1px solid rgba(216,227,244,0.9); border-radius: 999px;
    background: #fff; color: #52627f; font-size: 11.5px; font-weight: 600; cursor: pointer;
    transition: background 120ms, border-color 120ms;
}
.rex-picker__btn:hover { background: #f0f4ff; border-color: #bfd0ef; }
.rex-picker__search { position: relative; }
.rex-picker__search input {
    width: 100%; height: 40px;
    border: 1.5px solid rgba(216,227,244,0.9); border-radius: 11px;
    background: #f8fbff; padding: 0 14px 0 40px;
    font-size: 13px; color: var(--navy); outline: none;
}
.rex-picker__search input:focus {
    border-color: #6b8fd6; background: #fff;
    box-shadow: 0 0 0 4px rgba(59,92,165,0.08);
}
.rex-picker__search svg {
    position: absolute; left: 13px; top: 50%;
    transform: translateY(-50%); color: #7b8aa8; pointer-events: none;
}
.rex-picker__list {
    display: grid; grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 10px; max-height: 380px; overflow: auto; padding-right: 2px;
}
.rex-session { position: relative; display: block; cursor: pointer; }
.rex-session input { position: absolute; opacity: 0; pointer-events: none; }
.rex-session__box {
    display: flex; flex-direction: column; gap: 5px;
    padding: 13px 15px;
    border: 1.5px solid rgba(216,227,244,0.9); border-radius: 13px;
    background: #fff;
    transition: border-color 140ms, box-shadow 140ms, transform 140ms;
}
.rex-session:hover .rex-session__box {
    border-color: #b0c4e8;
    box-shadow: 0 4px 12px rgba(31,75,143,0.07);
}
.rex-session__top {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 10px;
}
.rex-session__title { font-size: 12.5px; font-weight: 700; color: var(--navy); line-height: 1.35; }
.rex-session__badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 36px; height: 22px; padding: 0 8px;
    border-radius: 999px; background: rgba(216,227,244,0.5);
    color: #52627f; font-size: 10.5px; font-weight: 700; flex-shrink: 0;
}
.rex-session__meta   { font-size: 11.5px; color: #6e7d99; }
.rex-session__center {
    font-size: 10px; font-weight: 700; letter-spacing: 0.06em;
    text-transform: uppercase; color: #1f4b8f;
}
.rex-session input:checked + .rex-session__box {
    border-color: var(--navy);
    box-shadow: 0 0 0 3px rgba(26,26,26,0.08);
    background: #f7f9ff;
    transform: translateY(-1px);
}
.rex-session.is-hidden { display: none; }
.rex-picker__empty {
    display: none; padding: 18px;
    border: 1px dashed rgba(216,227,244,0.9);
    border-radius: 13px; text-align: center;
    color: #72809f; font-size: 13px;
}
.rex-picker__empty.is-visible { display: block; }
.rex-picker__help { font-size: 11.5px; color: #a0abc0; }

/* ── Breakdown cards ── */
.breakdown-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-radius: 10px;
    border: 1px solid rgba(216,227,244,0.7);
    background: linear-gradient(180deg, #f8faff, #eef3fb);
    transition: border-color 120ms;
}
.breakdown-row:hover { border-color: #bfd0ef; }
.breakdown-label { font-size: 13px; font-weight: 600; color: var(--navy); }
.breakdown-count {
    font-family: 'Syne', sans-serif;
    font-size: 16px; font-weight: 700;
    color: var(--navy); letter-spacing: -0.02em;
}

@media (max-width: 860px) {
    .rex-picker__list { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="page-shell stack-grid theme-page theme-ink">

    {{-- Intro --}}
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Aper&#231;u du rapport</div>
            <div class="page-intro-text">
                S&#233;lectionnez une ou plusieurs sessions. L&#8217;ordre reste chronologique, puis alphab&#233;tique par candidat.
            </div>
        </div>
        <div class="actions-inline">
            <span class="badge neutral">{{ $overview['total'] }} ligne{{ $overview['total'] > 1 ? 's' : '' }}</span>
            <span class="badge success">{{ count($selectedSessionIds) }} session{{ count($selectedSessionIds) > 1 ? 's' : '' }} cibl&#233;e{{ count($selectedSessionIds) > 1 ? 's' : '' }}</span>
        </div>
    </section>

    {{-- Filtres --}}
    <section class="data-panel">
        <div class="table-card-header">
            <div class="table-card-title">Filtres de s&#233;lection</div>
        </div>
        <form method="GET" class="card-section stack-grid">
            <div class="toolbar-grid">
                <div class="search-wrap">
                    <span class="search-icon">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </span>
                    <input class="search-input" type="text" name="q" value="{{ request('q') }}" placeholder="&#201;tudiant, centre, session...">
                </div>
                <div class="field">
                    <label>Centre</label>
                    <select name="center_id">
                        <option value="">Tous les centres</option>
                        @foreach ($centerOptions as $center)
                            <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>
                                {{ $center->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Niveau</label>
                    <select name="level">
                        <option value="">Tous niveaux</option>
                        @foreach (\App\Models\ExamSession::LEVELS as $levelOption)
                            <option value="{{ $levelOption }}" {{ request('level') === $levelOption ? 'selected' : '' }}>{{ $levelOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Paiement</label>
                    <select name="payment_status">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid"    {{ request('payment_status') === 'paid'    ? 'selected' : '' }}>Pay&#233;</option>
                    </select>
                </div>
                <div class="field">
                    <label>Statut</label>
                    <select name="status">
                        <option value="">Tous</option>
                        <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annul&#233;e</option>
                    </select>
                </div>
            </div>

            {{-- Session picker --}}
            <div class="rex-picker" id="report-session-picker">
                <div class="rex-picker__head">
                    <div>
                        <div class="rex-picker__title">Sessions &#224; combiner</div>
                        <div class="rex-picker__text">Cochez les sessions &#224; inclure dans l&#8217;export.</div>
                    </div>
                    <div class="rex-picker__tools">
                        <span class="rex-picker__count" id="report-session-count">{{ count($selectedSessionIds) }}</span>
                        <button type="button" class="rex-picker__btn" data-session-action="all">Tout s&#233;lectionner</button>
                        <button type="button" class="rex-picker__btn" data-session-action="clear">Effacer</button>
                    </div>
                </div>

                <div class="rex-picker__search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="report-session-search" placeholder="Rechercher une session, un centre ou un niveau...">
                </div>

                <div class="rex-picker__list" id="report-session-list">
                    @foreach ($sessionOptions as $session)
                        @php
                            $sessionId  = (int) $session->id;
                            $isSelected = in_array($sessionId, $selectedSessionIds, true);
                            $sessionSearch = \Illuminate\Support\Str::lower(trim(
                                ($session->title ?? '') . ' ' .
                                (optional($session->center)->name ?? '') . ' ' .
                                ($session->level ?? '') . ' ' .
                                (optional($session->exam_date)->format('d/m/Y') ?? '')
                            ));
                        @endphp
                        <label class="rex-session" data-session-search="{{ $sessionSearch }}">
                            <input type="checkbox" name="session_ids[]" value="{{ $sessionId }}" {{ $isSelected ? 'checked' : '' }}>
                            <span class="rex-session__box">
                                <span class="rex-session__top">
                                    <span class="rex-session__title">{{ $session->title }}</span>
                                    <span class="rex-session__badge">{{ $session->level }}</span>
                                </span>
                                <span class="rex-session__center">{{ optional($session->center)->name ?: 'Centre' }}</span>
                                <span class="rex-session__meta">
                                    {{ optional($session->exam_date)->format('d/m/Y') ?: '--/--/----' }}
                                    @if($session->city) &mdash; {{ $session->city }} @endif
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>

                <div class="rex-picker__empty" id="report-session-empty">
                    Aucune session ne correspond &#224; la recherche.
                </div>
                <div class="rex-picker__help">
                    Laissez vide pour exporter toutes les sessions correspondant aux autres filtres.
                </div>
            </div>

            <div class="toolbar-actions">
                @if (request()->query())
                    <a class="btn btn-ghost" href="{{ route('registrations.export') }}">R&#233;initialiser</a>
                @endif
                <button class="btn btn-secondary" type="submit">Actualiser l&#8217;aper&#231;u</button>
                <a class="btn btn-ghost" href="{{ route('registrations.export.pdf', request()->query()) }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    PDF
                </a>
                <a class="btn btn-primary" href="{{ route('registrations.export.download', request()->query()) }}" data-loading="true" data-loading-text="Export XLSX en cours...">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    XLSX
                </a>
            </div>
        </form>
    </section>

    {{-- R&#233;partition --}}
    <div class="split">
        <section class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">R&#233;partition par niveau</div>
            </div>
            <div class="card-section stack-grid">
                @forelse ($levelBreakdown as $item)
                    <div class="breakdown-row">
                        <span class="breakdown-label">{{ $item->level ?: 'Non d&#233;fini' }}</span>
                        <span class="breakdown-count">{{ $item->total }}</span>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-title">Aucune donn&#233;e</div>
                        <div class="empty-state-desc">Aucune inscription ne correspond aux filtres actifs.</div>
                    </div>
                @endforelse
            </div>
        </section>
        <section class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">Top centres</div>
            </div>
            <div class="card-section stack-grid">
                @forelse ($centerBreakdown as $item)
                    <div class="breakdown-row">
                        <span class="breakdown-label">{{ $item->name }}</span>
                        <span class="breakdown-count">{{ $item->total }}</span>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-title">Aucune donn&#233;e</div>
                        <div class="empty-state-desc">Aucun centre ne ressort pour la s&#233;lection courante.</div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Pr&#233;visualisation --}}
    <section class="table-card">
        <div class="table-card-header">
            <div>
                <div class="table-card-title">Aper&#231;u avant export</div>
                <div class="td-muted">Le fichier contient le candidat, sa localisation, son centre et sa session.</div>
            </div>
            <span class="badge neutral">{{ $preview->total() }} r&#233;sultat{{ $preview->total() > 1 ? 's' : '' }}</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Pr&#233;nom</th>
                        <th>Email</th>
                        <th>Naissance</th>
                        <th>Langue</th>
                        <th>Sexe</th>
                        <th>Ville / Pays</th>
                        <th>Centre</th>
                        <th>Session</th>
                        <th>Date session</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($preview as $registration)
                        <tr>
                            <td>{{ optional($registration->student)->last_name }}</td>
                            <td>{{ optional($registration->student)->first_name }}</td>
                            <td class="td-muted">{{ optional($registration->student)->email }}</td>
                            <td class="td-muted">
                                {{ optional(optional($registration->student)->date_of_birth)->format('d.m.Y') ?: 'Non renseign&#233;e' }}
                                <div>{{ optional($registration->student)->place_of_birth }}</div>
                            </td>
                            <td class="td-muted">{{ \App\Models\Student::languageLabel(optional($registration->student)->base_language) ?: 'Non renseign&#233;e' }}</td>
                            <td class="td-muted">{{ \App\Models\Student::genderLabel(optional($registration->student)->gender) ?: 'Non renseign&#233;' }}</td>
                            <td class="td-muted">
                                {{ optional($registration->student)->city ?: 'Ville N/A' }}
                                &mdash; {{ optional($registration->student)->country ?: 'Pays N/A' }}
                            </td>
                            <td class="td-muted">{{ optional($registration->center)->name }}</td>
                            <td class="td-muted">{{ optional($registration->examSession)->title }}</td>
                            <td class="td-muted td-mono">{{ optional(optional($registration->examSession)->exam_date)->format('d.m.Y') ?: 'Non d&#233;finie' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-title">Aucun r&#233;sultat</div>
                                    <div class="empty-state-desc">Aucune inscription ne correspond aux filtres choisis.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($preview->hasPages())
            <div class="pagination">{{ $preview->links() }}</div>
        @endif
    </section>

</div>
@endsection

@push('scripts')
<script>
(function () {
    var searchInput   = document.getElementById('report-session-search');
    var sessionList   = document.getElementById('report-session-list');
    var emptyState    = document.getElementById('report-session-empty');
    var countBadge    = document.getElementById('report-session-count');

    if (!searchInput || !sessionList || !emptyState || !countBadge) return;

    var sessionItems      = Array.prototype.slice.call(sessionList.querySelectorAll('.rex-session'));
    var sessionCheckboxes = Array.prototype.slice.call(sessionList.querySelectorAll('input[type="checkbox"]'));
    var actionButtons     = Array.prototype.slice.call(document.querySelectorAll('[data-session-action]'));

    function updateCount() {
        var n = sessionCheckboxes.filter(function(c){ return c.checked; }).length;
        countBadge.textContent = n;
    }

    function filterSessions() {
        var term = searchInput.value.trim().toLowerCase();
        var visible = 0;
        sessionItems.forEach(function(item){
            var match = !term || item.dataset.sessionSearch.indexOf(term) !== -1;
            item.classList.toggle('is-hidden', !match);
            if (match) visible++;
        });
        emptyState.classList.toggle('is-visible', visible === 0);
    }

    actionButtons.forEach(function(btn){
        btn.addEventListener('click', function(){
            var action = btn.getAttribute('data-session-action');
            sessionCheckboxes.forEach(function(c){ c.checked = action === 'all'; });
            updateCount();
        });
    });

    sessionCheckboxes.forEach(function(c){ c.addEventListener('change', updateCount); });
    searchInput.addEventListener('input', filterSessions);

    updateCount();
    filterSessions();
})();
</script>
@endpush
