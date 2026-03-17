@extends('layouts.app')

@section('page_title', 'Correction d inscription')
@section('page_kicker', 'Administration')
@section('page_subtitle', 'Modifiez la fiche d inscription dans un formulaire plus lisible et gardez l historique a cote.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('registrations.index') }}">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Retour
    </a>
    @if ($registration->payment_status === 'paid')
        <a class="btn btn-secondary" href="{{ route('registrations.receipt.download', $registration) }}">Telecharger le PDF</a>
    @else
        <button class="btn btn-secondary" type="button" disabled title="Le reÁu sera disponible aprËs paiement">Telecharger le PDF</button>
    @endif
@endsection

@section('content')
<div class="page-shell stack-grid theme-page theme-amber">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Correction d inscription</div>
            <div class="page-intro-text">La fiche reprend l identite, la session et le paiement. Les informations de synthese restent visibles en haut de page.</div>
        </div>
        <div class="actions-inline">
            <span class="badge neutral">{{ $registration->receipt_number }}</span>
            <span class="badge {{ $registration->payment_status === 'paid' ? 'success' : 'warning' }}">{{ $registration->payment_status === 'paid' ? 'Paye' : 'En attente' }}</span>
            <span class="badge {{ $registration->status === 'active' ? 'success' : 'danger' }}">{{ $registration->status === 'active' ? 'Active' : 'Annulee' }}</span>
        </div>
    </section>

    <section class="meta-grid">
        <div class="meta-box"><div class="meta-label">Centre</div><div class="meta-value">{{ optional($registration->center)->name }}</div></div>
        <div class="meta-box"><div class="meta-label">Session</div><div class="meta-value">{{ optional($registration->examSession)->title }}</div></div>
        <div class="meta-box"><div class="meta-label">Montant</div><div class="meta-value td-mono">{{ number_format($registration->amount, 0, ',', ' ') }} FCFA</div></div>
        <div class="meta-box"><div class="meta-label">Creee le</div><div class="meta-value">{{ optional($registration->registered_at)->format('d/m/Y H:i') ?: optional($registration->created_at)->format('d/m/Y H:i') }}</div></div>
    </section>

    <div class="split wide" style="align-items: start;">
        <section class="table-card">
            <div class="table-card-header"><div><div class="table-card-title">Modifier l inscription</div><div class="td-muted">Les changements sont journalises automatiquement.</div></div></div>
            <form method="POST" action="{{ route('registrations.update', $registration) }}" class="card-section stack-grid" data-loading="true" data-loading-text="Mise a jour en cours...">
                @csrf
                @method('PUT')
                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Session</div>
                    <div class="field"><label>Session d examen</label><select name="exam_session_id" required>@foreach ($sessions as $session)<option value="{{ $session->id }}" {{ (int) old('exam_session_id', $registration->exam_session_id) === $session->id ? 'selected' : '' }}>{{ $session->title }} - {{ $session->city }} - {{ $session->level }}</option>@endforeach</select>@error('exam_session_id')<div class="field-error">{{ $message }}</div>@enderror</div>
                </div>
                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Identite</div>
                    <div class="form-grid">
                        <div class="field"><label>Nom</label><input type="text" name="last_name" value="{{ old('last_name', $registration->student->last_name) }}" required>@error('last_name')<div class="field-error">{{ $message }}</div>@enderror</div>
                        <div class="field"><label>Prenom</label><input type="text" name="first_name" value="{{ old('first_name', $registration->student->first_name) }}" required>@error('first_name')<div class="field-error">{{ $message }}</div>@enderror</div>
                        <div class="field"><label>E-mail</label><input type="email" name="email" value="{{ old('email', $registration->student->email) }}">@error('email')<div class="field-error">{{ $message }}</div>@enderror</div>
                        <div class="field"><label>Sexe</label><select name="gender"><option value="">Selectionner</option><option value="M" {{ \App\Models\Student::normalizeGender(old('gender', $registration->student->gender)) === 'M' ? 'selected' : '' }}>Male</option><option value="F" {{ \App\Models\Student::normalizeGender(old('gender', $registration->student->gender)) === 'F' ? 'selected' : '' }}>Female</option></select></div>
                        <div class="field"><label>Date de naissance</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional(optional($registration->student)->date_of_birth)->format('Y-m-d')) }}"></div>
                        <div class="field"><label>Lieu de naissance</label><input type="text" name="place_of_birth" value="{{ old('place_of_birth', $registration->student->place_of_birth) }}"></div>
                    </div>
                </div>
                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Localisation et paiement</div>
                    <div class="form-grid">
                        <div class="field"><label>Ville</label><input type="text" name="city" value="{{ old('city', $registration->student->city) }}"></div>
                        <div class="field"><label>Pays</label><input type="text" name="country" value="{{ old('country', $registration->student->country ?? 'CM - Cameroon') }}" required></div>
                        <div class="field"><label>Langue de base</label><select name="base_language"><option value="">Selectionner</option><option value="English" {{ \App\Models\Student::normalizeLanguage(old('base_language', $registration->student->base_language)) === 'English' ? 'selected' : '' }}>002-English/English</option><option value="French" {{ \App\Models\Student::normalizeLanguage(old('base_language', $registration->student->base_language)) === 'French' ? 'selected' : '' }}>003-French/Franz√∂sisch</option></select></div>
                        <div class="field"><label>Paiement</label><select name="payment_status"><option value="pending" {{ old('payment_status', $registration->payment_status) === 'pending' ? 'selected' : '' }}>En attente</option><option value="paid" {{ old('payment_status', $registration->payment_status) === 'paid' ? 'selected' : '' }}>Paye</option></select></div>
                    </div>
                </div>
                <div class="toolbar-actions"><a class="btn btn-ghost" href="{{ route('registrations.index') }}">Annuler</a><button class="btn btn-primary" type="submit">Enregistrer les corrections</button></div>
            </form>
        </section>

        <section class="table-card">
            <div class="table-card-header"><div><div class="table-card-title">Historique d audit</div><div class="td-muted">Les operations realisees sur cette inscription restent consultables ici.</div></div></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Date</th><th>Action</th><th>Details</th></tr></thead>
                    <tbody>
                        @forelse ($registration->audits as $audit)
                            <tr><td class="td-muted td-mono">{{ optional($audit->created_at)->format('d/m/Y H:i') }}</td><td><span class="badge neutral">{{ $audit->action }}</span></td><td class="td-muted">{{ $audit->details }}</td></tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state"><div class="empty-state-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="empty-state-title">Aucune trace d audit</div><div class="empty-state-desc">Aucune modification n a encore ete journalisee pour cette inscription.</div></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection




