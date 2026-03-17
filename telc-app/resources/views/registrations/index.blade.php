@extends('layouts.app')

@section('page_title', 'Inscriptions')
@section('page_kicker', 'Operations')
@section('page_subtitle', 'La page Inscriptions suit les sessions, les montants, les paiements et les recus.')

@section('page_actions')
    @if ($user->isSecretary() || $user->isCenterAdmin())
        <a class="btn btn-primary" href="{{ route('registrations.create', request()->only('center_id')) }}">Nouvelle inscription</a>
    @endif
    @if ($user->isSuperAdmin() || $user->isSecretary() || $user->isCenterAdmin())
        <a class="btn btn-secondary" href="{{ route('registrations.export', request()->query()) }}">Rapports et export</a>
    @endif
@endsection

@push('styles')
<style>
/* -- Stats bar -- */
.rx-stats{display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #e9edf2;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:14px 22px;margin-bottom:18px;flex-wrap:wrap}
.rx-stat{display:flex;align-items:center;gap:8px}
.rx-stat__dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.rx-stat__dot--blue{background:#6366f1}.rx-stat__dot--green{background:#22c55e}.rx-stat__dot--amber{background:#f59e0b}
.rx-stat__val{font-size:15px;font-weight:700;color:#0f172a}
.rx-stat__lbl{font-size:12.5px;color:#64748b}
.rx-divider{width:1px;height:20px;background:#e2e8f0;flex-shrink:0}

/* -- KPI row -- */
.rx-kpi-row{display:grid;grid-template-columns:1.6fr 1fr 1fr;gap:14px;margin-bottom:24px}
.rx-kpi{background:#fff;border:1px solid #e9edf2;border-radius:14px;box-shadow:0 2px 8px rgba(0,0,0,.04);padding:20px 22px 18px;display:flex;flex-direction:column;gap:4px;transition:box-shadow .2s,transform .2s}
.rx-kpi:hover{box-shadow:0 6px 20px rgba(0,0,0,.08);transform:translateY(-2px)}
.rx-kpi--primary{background:linear-gradient(145deg,#fff 60%,#f0f4ff);border-color:#c7d2fe}
.rx-kpi__icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;flex-shrink:0}
.rx-kpi__icon--red{background:#fff1f2;color:#f43f5e}
.rx-kpi__icon--navy{background:#eff6ff;color:#1d4ed8}
.rx-kpi__icon--green{background:#f0fdf4;color:#16a34a}
.rx-kpi__label{font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8}
.rx-kpi__value{font-size:2rem;font-weight:700;color:#0f172a;line-height:1.05}
.rx-kpi--primary .rx-kpi__value{font-size:2.6rem;color:#312e81}
.rx-kpi__sub{font-size:12px;color:#94a3b8;margin-top:2px}

/* -- Filter bar -- */
.rx-filterbar{background:#fff;border:1px solid #e9edf2;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:16px 20px;margin-bottom:24px}
.rx-filterbar__top{display:flex;align-items:center;gap:10px;margin-bottom:12px}
.rx-filterbar__label{display:flex;align-items:center;gap:6px;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;white-space:nowrap}
.rx-filterbar__search{flex:1;position:relative;max-width:340px}
.rx-filterbar__search svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none}
.rx-filterbar__search input{width:100%;height:36px;padding:0 11px 0 34px;font-size:13px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;color:#1e293b;outline:none;transition:border-color .15s,box-shadow .15s}
.rx-filterbar__search input:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);background:#fff}
.rx-filterbar__grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:8px;margin-bottom:12px}
.rx-filterbar__grid select{height:36px;padding:0 11px;font-size:13px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;color:#1e293b;outline:none;width:100%;transition:border-color .15s,box-shadow .15s}
.rx-filterbar__grid select:focus{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.1);background:#fff}
.rx-filterbar__actions{display:flex;align-items:center;justify-content:flex-end;gap:8px}
.rx-btn-apply{height:36px;padding:0 16px;font-size:13px;font-weight:600;border:none;border-radius:8px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;cursor:pointer;transition:opacity .15s,transform .1s}
.rx-btn-apply:hover{opacity:.9;transform:translateY(-1px)}
.rx-btn-reset{display:flex;align-items:center;gap:5px;height:36px;padding:0 12px;font-size:12.5px;font-weight:500;color:#64748b;background:transparent;border:1px solid #e2e8f0;border-radius:8px;text-decoration:none;transition:background .15s}
.rx-btn-reset:hover{background:#f1f5f9}

/* -- Table card -- */
.rx-table-card{background:#fff;border:1px solid #e9edf2;border-radius:14px;box-shadow:0 2px 8px rgba(0,0,0,.04);overflow:hidden}
.rx-table-card__head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f1f5f9}
.rx-table-card__title{font-size:14px;font-weight:700;color:#0f172a}
.rx-table-card__sub{font-size:12px;color:#94a3b8;margin-top:2px}
.rx-table-card__count{font-size:12px;font-weight:600;color:#94a3b8;background:#f8fafc;border:1px solid #e9edf2;border-radius:99px;padding:3px 10px}

/* -- Responsive -- */
@media(max-width:900px){
  .rx-kpi-row{grid-template-columns:1fr 1fr}
  .rx-kpi--primary{grid-column:span 2}
}
@media(max-width:600px){
  .rx-kpi-row{grid-template-columns:1fr}
  .rx-kpi--primary{grid-column:auto}
  .rx-filterbar__grid{grid-template-columns:1fr 1fr}
}
</style>
@endpush

@section('content')
{{-- Filtres --}}
<div class="rx-filterbar">
    <form method="GET">
        <div class="rx-filterbar__top">
            <div class="rx-filterbar__label">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filtres
            </div>
            <div class="rx-filterbar__search">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Etudiant, recu, centre, session...">
            </div>
        </div>

        <div class="rx-filterbar__grid">
            @if ($centerOptions->isNotEmpty())
            <select name="center_id">
                <option value="">Tous les centres</option>
                @foreach ($centerOptions as $center)
                    <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                @endforeach
            </select>
            @endif
            <select name="session_id">
                <option value="">Toutes les sessions</option>
                @foreach ($sessionOptions as $session)
                    <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>{{ $session->title }} - {{ optional($session->center)->name }}</option>
                @endforeach
            </select>
            <select name="level">
                <option value="">Tous niveaux</option>
                @foreach (\App\Models\ExamSession::LEVELS as $levelOption)
                    <option value="{{ $levelOption }}" {{ request('level') === $levelOption ? 'selected' : '' }}>{{ $levelOption }}</option>
                @endforeach
            </select>
            <select name="payment_status">
                <option value="">Paiement : Tous</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="paid"    {{ request('payment_status') === 'paid'    ? 'selected' : '' }}>Paye</option>
            </select>
            <select name="status">
                <option value="">Statut : Tous</option>
                <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulee</option>
            </select>
        </div>

        <div class="rx-filterbar__actions">
            @if (request()->hasAny(['q','center_id','session_id','level','payment_status','status']))
                <a class="rx-btn-reset" href="{{ route('registrations.index') }}">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Effacer
                </a>
            @endif
            <button type="submit" class="rx-btn-apply">Appliquer</button>
        </div>
    </form>
</div>
{{-- Stats --}}
<div class="rx-stats">
    <div class="rx-stat">
        <div class="rx-stat__dot rx-stat__dot--blue"></div>
        <span class="rx-stat__val">{{ $overview['total'] }}</span>
        <span class="rx-stat__lbl">inscription{{ $overview['total'] > 1 ? 's' : '' }} au total</span>
    </div>
    <div class="rx-divider"></div>
    <div class="rx-stat">
        <div class="rx-stat__dot rx-stat__dot--green"></div>
        <span class="rx-stat__val">{{ $overview['paid'] }}</span>
        <span class="rx-stat__lbl">payee{{ $overview['paid'] > 1 ? 's' : '' }}</span>
    </div>
    <div class="rx-divider"></div>
    <div class="rx-stat">
        <div class="rx-stat__dot rx-stat__dot--amber"></div>
        <span class="rx-stat__val">{{ $overview['pending'] }}</span>
        <span class="rx-stat__lbl">en attente</span>
    </div>
</div>

{{-- KPI --}}
<div class="rx-kpi-row">
    <article class="rx-kpi rx-kpi--primary">
        <div class="rx-kpi__icon rx-kpi__icon--red">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="rx-kpi__label">Inscriptions</div>
        <div class="rx-kpi__value">{{ $overview['total'] }}</div>
        <div class="rx-kpi__sub">dossiers visibles</div>
    </article>
    <article class="rx-kpi">
        <div class="rx-kpi__icon rx-kpi__icon--navy">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="rx-kpi__label">Montant cumule</div>
        <div class="rx-kpi__value" style="font-size:1.5rem;">{{ number_format($overview['amount'], 0, ',', ' ') }}</div>
        <div class="rx-kpi__sub">FCFA sur la selection</div>
    </article>
    <article class="rx-kpi">
        <div class="rx-kpi__icon rx-kpi__icon--green">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><polyline points="8 12 11 15 16 9"/></svg>
        </div>
        <div class="rx-kpi__label">Paiements</div>
        <div class="rx-kpi__value">{{ $overview['paid'] }}<span style="font-size:1rem;font-weight:500;color:#94a3b8;">/{{ $overview['total'] }}</span></div>
        <div class="rx-kpi__sub">recus comme payes</div>
    </article>
</div>



{{-- Table --}}
<div class="rx-table-card">
    <div class="rx-table-card__head">
        <div>
            <div class="rx-table-card__title">Liste des inscriptions</div>
            <div class="rx-table-card__sub">Les centres et sessions apparaissent explicitement pour eviter toute confusion.</div>
        </div>
        <span class="rx-table-card__count">{{ $registrations->total() }} resultat{{ $registrations->total() > 1 ? 's' : '' }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Etudiant</th>
                    <th>Centre</th>
                    <th>Session</th>
                    <th>Date exam</th>
                    <th>Montant</th>
                    <th>Paiement</th>
                    <th>Statut</th>
                    <th>Recu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $registration)
                    @php
                        $isOwnerSecretary = $user->isSecretary() && (int) $registration->registered_by === (int) $user->id;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ optional($registration->student)->full_name }}</strong>
                            <div class="td-muted">{{ optional($registration->student)->email }}</div>
                        </td>
                        <td class="td-muted">{{ optional($registration->center)->name }}</td>
                        <td class="td-muted">{{ optional($registration->examSession)->title }}</td>
                        <td class="td-muted td-mono">{{ optional(optional($registration->examSession)->exam_date)->format('d/m/Y') ?: 'Non definie' }}</td>
                        <td class="td-mono">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</td>
                        <td><span class="badge {{ $registration->payment_status === 'paid' ? 'success' : 'warning' }}">{{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}</span></td>
                        <td><span class="badge {{ $registration->status === 'active' ? 'success' : 'danger' }}">{{ $registration->status === 'active' ? 'Active' : 'Annulee' }}</span></td>
                        <td class="td-muted td-mono">{{ $registration->receipt_number }}</td>
                        <td>
                            <div class="table-actions">
                                @if ($registration->payment_status === 'paid')
                                    <a class="btn btn-ghost btn-sm" href="{{ route('registrations.receipt', $registration) }}">Voir</a>
                                    <button class="btn btn-ghost btn-sm" onclick="generatePDFFromList('{{ route('registrations.receipt', $registration) }}');">PDF</button>
                                @else
                                    <button class="btn btn-ghost btn-sm" disabled title="Le re�u sera disponible apr�s paiement">Voir</button>
                                    <button class="btn btn-ghost btn-sm" disabled title="Le PDF sera disponible apr�s paiement">PDF</button>
                                @endif
                                @if ((($user->isSecretary() && $isOwnerSecretary) || $user->isCenterAdmin()) && $registration->payment_status !== 'paid' && $registration->status === 'active')
                                    <form method="POST" action="{{ route('registrations.pay', $registration) }}" data-loading="true" data-loading-text="Validation du paiement en cours...">@csrf<button class="btn btn-secondary btn-sm" type="submit">Marquer paye</button></form>
                                @endif
                                @if ((($user->isSecretary() && $isOwnerSecretary) || $user->isCenterAdmin() || $user->isSuperAdmin()) && $registration->status === 'active')
                                    <a class="btn btn-ghost btn-sm" href="{{ route('registrations.edit', $registration) }}">Corriger</a>
                                @endif
                                @if ($user->isSuperAdmin())
                                    @if ($registration->status === 'active')
                                        <form method="POST" action="{{ route('registrations.cancel', $registration) }}" data-loading="true" data-loading-text="Annulation en cours...">@csrf<input type="hidden" name="cancel_reason" value="Annulation admin"><button class="btn btn-danger btn-sm" type="submit">Annuler</button></form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-title">Aucune inscription</div>
                                <div class="empty-state-desc">Aucune inscription ne correspond aux filtres selectionnes.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($registrations->hasPages())
        <div class="pagination">{{ $registrations->links() }}</div>
    @endif
</div>

@endsection

<script>
function generatePDFFromList(receiptUrl) {
    // Ouvrir la page du reçu dans une nouvelle fenêtre
    const receiptWindow = window.open(receiptUrl, '_blank');
    
    // Attendre que la page soit chargée puis générer le PDF
    receiptWindow.onload = function() {
        setTimeout(() => {
            // Appeler la fonction generatePDF de la page du reçu
            receiptWindow.generatePDF();
        }, 1000);
    };
}
</script>





