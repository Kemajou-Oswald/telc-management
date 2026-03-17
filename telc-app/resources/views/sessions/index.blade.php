@extends('layouts.app')

@section('page_title', "Sessions d'examen")
@section('page_kicker', 'Pilotage')
@section('page_subtitle')
    Suivez les sessions, filtrez par centre et consultez leur détail en un clic.
@endsection

@section('page_actions')
    @if ($user->isSuperAdmin())
        <a class="btn btn-primary" href="{{ route('sessions.create', request()->only('center_id')) }}">Nouvelle session</a>
    @endif
@endsection

@push('styles')
<style>
.sx-stats{display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #e9edf2;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 22px;margin-bottom:18px;flex-wrap:wrap}
.sx-stat{display:flex;align-items:center;gap:8px}
.sx-stat__dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.sx-stat__dot--blue{background:#6366f1}.sx-stat__dot--green{background:#22c55e}.sx-stat__dot--amber{background:#f59e0b}.sx-stat__dot--slate{background:#94a3b8}
.sx-stat__val{font-size:15px;font-weight:700;color:#0f172a}
.sx-stat__lbl{font-size:12.5px;color:#64748b}
.sx-divider{width:1px;height:20px;background:#e2e8f0;flex-shrink:0}
.sx-filterbar{background:#fff;border:1px solid #e9edf2;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:12px 18px;margin-bottom:24px}
.sx-filterbar__form{display:flex;align-items:center;gap:10px;flex-wrap:nowrap}
.sx-filterbar__label{display:flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;padding-right:14px;border-right:1px solid #e9edf2;white-space:nowrap;flex-shrink:0}
.sx-filterbar select{height:36px;padding:0 11px;font-size:13px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;color:#1e293b;outline:none;min-width:0;flex:0 1 190px;transition:border-color .15s,box-shadow .15s}
.sx-filterbar select:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);background:#fff}
.sx-filterbar__actions{margin-left:auto;display:flex;align-items:center;gap:8px;flex-shrink:0}
.sx-btn-apply{height:36px;padding:0 16px;font-size:13px;font-weight:600;border:none;border-radius:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;cursor:pointer;transition:opacity .15s,transform .1s}
.sx-btn-apply:hover{opacity:.9;transform:translateY(-1px)}
.sx-btn-reset{display:flex;align-items:center;gap:5px;height:36px;padding:0 12px;font-size:12.5px;font-weight:500;color:#64748b;background:transparent;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;transition:background .15s}
.sx-btn-reset:hover{background:#f1f5f9}

.sx-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:18px;margin-bottom:28px}
.sx-card{background:#ffffff;border:1px solid #e3e7ef;border-radius:20px;box-shadow:0 14px 34px rgba(15,23,42,.08);padding:18px 18px 16px;display:flex;flex-direction:column;position:relative;overflow:hidden;transition:box-shadow .2s,transform .2s;border-left:5px solid transparent;cursor:pointer}
.sx-card::after{content:'';position:absolute;inset:0;border-radius:20px;box-shadow:inset 0 0 0 1px rgba(255,255,255,.55);pointer-events:none}
.sx-card:hover{box-shadow:0 18px 36px rgba(15,23,42,.12);transform:translateY(-2px)}
.sx-card--open{border-left-color:#22c55e}
.sx-card--draft{border-left-color:#f59e0b}
.sx-card--closed{border-left-color:#94a3b8}

.sx-card__head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;margin-top:2px;gap:12px}
.sx-card__meta{display:flex;flex-direction:column;gap:5px;min-width:0}
.sx-card__center{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8}
.sx-card__level{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:800;color:#4f46e5;background:#eef2ff;border-radius:8px;padding:2px 9px;width:fit-content}
.sx-card__badge{font-size:11px;font-weight:800;padding:4px 11px;border-radius:999px;flex-shrink:0;letter-spacing:.02em}
.sx-card__badge--open{background:#dcfce7;color:#15803d}
.sx-card__badge--draft{background:#fef3c7;color:#b45309}
.sx-card__badge--closed{background:#f1f5f9;color:#64748b}
.sx-card__title{font-size:16px;font-weight:800;color:#0f172a;line-height:1.35;margin-bottom:4px}
.sx-card__city{display:flex;align-items:center;gap:6px;font-size:12.5px;color:#7c8aa5;margin-bottom:14px}
.sx-card__infos{display:flex;flex-direction:column;gap:8px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #eef2f7}
.sx-card__info{display:flex;align-items:center;gap:8px;font-size:12.5px;color:#475569}
.sx-card__info svg{flex-shrink:0;color:#94a3b8}

.sx-card__foot{display:grid;grid-template-columns:1fr auto;gap:12px;align-items:center;margin-top:auto;position:relative;z-index:1}
.sx-card__count{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#64748b}
.sx-card__actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:8px;padding:8px;border-radius:14px;background:#f8fafc;border:1px solid #eef2f7}

.sx-card__link{display:inline-flex;align-items:center;justify-content:center;height:36px;padding:0 12px;font-size:12px;font-weight:800;color:#334155;text-decoration:none;border-radius:10px;background:#fff;border:1px solid #e2e8f0}
.sx-card__link:hover{background:#f1f5f9}

.sx-card__action-form{display:flex;align-items:center;gap:6px}
.sx-card__select{height:36px;padding:0 10px;font-size:12px;border:1px solid #dbe4f3;border-radius:10px;background:#fff;color:#334155;outline:none;min-width:108px;transition:border-color .15s,box-shadow .15s}
.sx-card__select:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.12)}

.sx-card__save{height:36px;padding:0 14px;font-size:12px;font-weight:800;border:none;border-radius:10px;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;cursor:pointer;white-space:nowrap;box-shadow:0 6px 16px rgba(37,99,235,.25);transition:transform .1s,box-shadow .15s,opacity .15s}
.sx-card__save:hover{transform:translateY(-1px);box-shadow:0 10px 22px rgba(37,99,235,.28)}

.sx-card__save--ghost{background:#fff;color:#475569;border:1px solid #e2e8f0;box-shadow:none}
.sx-card__save--ghost:hover{background:#f1f5f9}
.sx-card__save--danger{background:#fff5f5;color:#b91c1c;border:1px solid #fecaca;box-shadow:none}
.sx-card__save--danger:hover{background:#fee2e2}
.sx-card__save--success{background:#ecfdf5;color:#047857;border:1px solid #bbf7d0;box-shadow:none}
.sx-card__save--success:hover{background:#d1fae5}

.sx-card__readonly{font-size:12px;color:#64748b;font-weight:700}
.sx-empty{display:flex;flex-direction:column;align-items:center;gap:12px;background:#fff;border:1px dashed #e2e8f0;border-radius:16px;padding:60px 24px;text-align:center;margin-bottom:24px}
.sx-empty__icon{width:56px;height:56px;border-radius:14px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;color:#94a3b8}
.sx-empty__title{font-size:15px;font-weight:700;color:#1e293b}
.sx-empty__desc{font-size:13px;color:#94a3b8}
.sx-pagination{display:flex;justify-content:center;margin-bottom:24px}
@media(max-width:900px){.sx-filterbar__form{flex-wrap:wrap}.sx-filterbar__label{display:none}.sx-filterbar__actions{margin-left:0;width:100%}}
@media(max-width:700px){.sx-grid{grid-template-columns:1fr}.sx-divider{display:none}.sx-filterbar select,.sx-btn-apply,.sx-btn-reset{width:100%;flex:1 1 100%}.sx-filterbar__actions{flex-wrap:wrap}.sx-card__foot{grid-template-columns:1fr}.sx-card__actions{justify-content:flex-start}}
</style>
@endpush

@section('content')
@php
    $totalCount = $counts['total'] ?? $sessions->count();
    $openCount = $counts['open'] ?? $sessions->where('status', 'open')->count();
    $draftCount = $counts['draft'] ?? $sessions->where('status', 'draft')->count();
    $closedCount = $counts['closed'] ?? $sessions->where('status', 'closed')->count();
@endphp

<div class="sx-stats">
    <div class="sx-stat"><div class="sx-stat__dot sx-stat__dot--blue"></div><span class="sx-stat__val">{{ $totalCount }}</span><span class="sx-stat__lbl">session{{ $totalCount > 1 ? 's' : '' }} au total</span></div>
    <div class="sx-divider"></div>
    <div class="sx-stat"><div class="sx-stat__dot sx-stat__dot--green"></div><span class="sx-stat__val">{{ $openCount }}</span><span class="sx-stat__lbl">ouverte{{ $openCount > 1 ? 's' : '' }}</span></div>
    <div class="sx-divider"></div>
    <div class="sx-stat"><div class="sx-stat__dot sx-stat__dot--amber"></div><span class="sx-stat__val">{{ $draftCount }}</span><span class="sx-stat__lbl">brouillon{{ $draftCount > 1 ? 's' : '' }}</span></div>
    <div class="sx-divider"></div>
    <div class="sx-stat"><div class="sx-stat__dot sx-stat__dot--slate"></div><span class="sx-stat__val">{{ $closedCount }}</span><span class="sx-stat__lbl">clôturée{{ $closedCount > 1 ? 's' : '' }}</span></div>
</div>

<div class="sx-filterbar">
    <form method="GET" class="sx-filterbar__form">
        <div class="sx-filterbar__label">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Filtres
        </div>
        @if ($centerOptions->isNotEmpty())
            <select name="center_id">
                <option value="">Tous les centres</option>
                @foreach ($centerOptions as $center)
                    <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                @endforeach
            </select>
        @endif
        <select name="status">
            <option value="">Tous les statuts</option>
            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Ouverte</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fermée</option>
        </select>
        <div class="sx-filterbar__actions">
            <button type="submit" class="sx-btn-apply">Appliquer</button>
            @if ($showArchived)
                <a href="{{ route('sessions.index', request()->only('center_id', 'status')) }}" class="sx-btn-reset">Voir les actives</a>
            @else
                <a href="{{ route('sessions.index', array_merge(request()->only('center_id', 'status'), ['archived' => 1])) }}" class="sx-btn-reset">Voir les archivées</a>
            @endif
            @if (request()->hasAny(['center_id', 'status']))
                <a href="{{ route('sessions.index') }}" class="sx-btn-reset">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Effacer
                </a>
            @endif
        </div>
    </form>
</div>

@if ($sessions->isEmpty())
    <div class="sx-empty">
        <div class="sx-empty__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div class="sx-empty__title">Aucune session disponible</div>
        <div class="sx-empty__desc">Créez une session ou modifiez les filtres.</div>
    </div>
@else
    <div class="sx-grid">
        @foreach ($sessions as $session)
            @php
                if ($session->status === 'open') {
                    $sc = 'open';
                    $sl = 'Ouverte';
                } elseif ($session->status === 'closed') {
                    $sc = 'closed';
                    $sl = 'Fermée';
                } else {
                    $sc = 'draft';
                    $sl = 'Brouillon';
                }
                $centerNames = $session->centers->pluck('name')->filter()->values();
                if ($centerNames->isEmpty() && optional($session->center)->name) {
                    $centerNames = collect([optional($session->center)->name]);
                }
                $centerLabel = $centerNames->first() ?? 'Centre non rattaché';
                if ($centerNames->count() > 1) {
                    $centerLabel .= ' + ' . ($centerNames->count() - 1);
                }
                $centerTitle = $centerNames->isNotEmpty() ? $centerNames->implode(', ') : $centerLabel;
            @endphp
            <article class="sx-card sx-card--{{ $sc }} js-session-card" data-href="{{ route('sessions.show', $session) }}" tabindex="0">
                <div class="sx-card__head">
                    <div class="sx-card__meta">
                        <span class="sx-card__center" title="{{ $centerTitle }}">{{ $centerLabel }}</span>
                        <span class="sx-card__level">{{ $session->level }}</span>
                    </div>
                    <span class="sx-card__badge sx-card__badge--{{ $sc }}">{{ $sl }}</span>
                </div>
                <div class="sx-card__title">{{ $session->title }}</div>
                <div class="sx-card__city">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $session->city ?: 'Ville non renseignée' }}
                </div>
                <div class="sx-card__infos">
                    <div class="sx-card__info">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        {{ number_format($session->price, 0, ',', ' ') }} FCFA
                    </div>
                    <div class="sx-card__info">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Examen : {{ optional($session->exam_date)->format('d/m/Y') ?: 'Non définie' }}
                    </div>
                    <div class="sx-card__info">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Inscriptions : {{ optional($session->opens_at)->format('d/m/Y') ?: '-' }} au {{ optional($session->closes_at)->format('d/m/Y') ?: 'Non définie' }}
                    </div>
                </div>
                <div class="sx-card__foot">
                    <div class="sx-card__count">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        {{ $session->registrations_count }} inscription{{ $session->registrations_count > 1 ? 's' : '' }}
                    </div>
                    @if ($user->isSuperAdmin())
                        <div class="sx-card__actions">
                            <a href="{{ route('sessions.edit', $session) }}" class="sx-card__link">Modifier</a>
                            <form method="POST" action="{{ route('sessions.toggle', $session) }}" class="sx-card__action-form" data-loading="true" data-loading-text="Changement de statut en cours...">
                                @csrf
                                @if (request('center_id'))<input type="hidden" name="center_id" value="{{ request('center_id') }}">@endif
                                @if (request('status'))<input type="hidden" name="status_filter" value="{{ request('status') }}">@endif
                                <select name="status" class="sx-card__select">
                                    <option value="draft" {{ $session->status === 'draft' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="open" {{ $session->status === 'open' ? 'selected' : '' }}>Ouverte</option>
                                    <option value="closed" {{ $session->status === 'closed' ? 'selected' : '' }}>Fermée</option>
                                </select>
                                <button type="submit" class="sx-card__save">OK</button>
                            </form>
                            @if ($showArchived)
                                <form method="POST" action="{{ route('sessions.archive', $session) }}" class="sx-card__action-form" data-loading="true" data-loading-text="Restauration en cours...">
                                    @csrf
                                    <input type="hidden" name="action" value="restore">
                                    @if (request('center_id'))<input type="hidden" name="center_id" value="{{ request('center_id') }}">@endif
                                    @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                                    <input type="hidden" name="archived" value="1">
                                    <button type="submit" class="sx-card__save sx-card__save--success">Restaurer</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('sessions.archive', $session) }}" class="sx-card__action-form" data-loading="true" data-loading-text="Archivage en cours...">
                                    @csrf
                                    <input type="hidden" name="action" value="archive">
                                    @if (request('center_id'))<input type="hidden" name="center_id" value="{{ request('center_id') }}">@endif
                                    @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                                    <button type="submit" class="sx-card__save sx-card__save--danger">Archiver</button>
                                </form>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('sessions.show', $session) }}" class="sx-card__link">Voir le détail</a>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
    <div class="sx-pagination">{{ $sessions->links() }}</div>
@endif
@endsection

@push('scripts')
<script>
document.querySelectorAll('.js-session-card').forEach(function (card) {
    card.addEventListener('click', function (event) {
        if (event.target.closest('a, button, form, select, option, input')) {
            return;
        }

        window.location = card.dataset.href;
    });

    card.addEventListener('keydown', function (event) {
        if ((event.key === 'Enter' || event.key === ' ') && !event.target.closest('a, button, form, select, option, input')) {
            event.preventDefault();
            window.location = card.dataset.href;
        }
    });
});
</script>
@endpush








