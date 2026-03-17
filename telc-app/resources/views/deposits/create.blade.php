@extends('layouts.app')

@section('page_title', 'Nouveau depot')
@section('page_kicker', 'Operations')
@section('page_subtitle', 'Enregistrez un depot bancaire avec son bordereau et, si besoin, sa session.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('deposits.index', request()->only('center_id')) }}">Retour</a>
@endsection

@include('partials.soft-form-styles')

@section('content')
<div class="sf-page">
    <section class="sf-card sf-card--main">
        <div class="sf-hero">
            <span class="sf-hero__eyebrow">Nouveau depot</span>
            <h2 class="sf-hero__title">Enregistrer un depot</h2>
            <p class="sf-hero__text">Ajoutez le montant, la date et le bordereau. Le depot restera en attente jusqu'a validation.</p>
            <div class="sf-hero__meta">
                <span class="sf-pill">{{ $sessions->count() }} session{{ $sessions->count() > 1 ? 's' : '' }} ouverte{{ $sessions->count() > 1 ? 's' : '' }}</span>
                <span class="sf-pill">Formats acceptes : PDF, JPG, PNG</span>
            </div>
        </div>

        <form method="POST" action="{{ route('deposits.store') }}" enctype="multipart/form-data" class="sf-form" data-loading="true" data-loading-text="Envoi du depot en cours...">
            @csrf

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Depot</div>
                        <div class="sf-section__text">Le centre et la session doivent appartenir a votre perimetre.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    @if ($accessibleCenters->count() > 1)
                        <div class="field">
                            <label for="center_id">Centre</label>
                            <select id="center_id" name="center_id" required>
                                <option value="">Selectionner un centre</option>
                                @foreach ($accessibleCenters as $center)
                                    <option value="{{ $center->id }}" {{ (string) old('center_id', $selectedCenterId) === (string) $center->id ? 'selected' : '' }}>{{ $center->name }} - {{ $center->city }}</option>
                                @endforeach
                            </select>
                            @error('center_id')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                    @endif
                    <div class="field {{ $accessibleCenters->count() <= 1 ? 'field--full' : '' }}">
                        <label for="exam_session_id">Session associee</label>
                        <select id="exam_session_id" name="exam_session_id">
                            <option value="">Aucune session precise</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" {{ (string) old('exam_session_id') === (string) $session->id ? 'selected' : '' }}>{{ $session->title }} - {{ optional($session->center)->name }}</option>
                            @endforeach
                        </select>
                        @error('exam_session_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="amount">Montant (FCFA)</label>
                        <input id="amount" type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" placeholder="50000" required>
                        @error('amount')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="deposit_date">Date du depot</label>
                        <input id="deposit_date" type="date" name="deposit_date" value="{{ old('deposit_date', now()->format('Y-m-d')) }}" required>
                        @error('deposit_date')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field field--full">
                        <label for="slip">Bordereau</label>
                        <input id="slip" type="file" name="slip" accept=".jpg,.jpeg,.png,.pdf" required>
                        <div class="sf-help">Taille maximale : 4 Mo.</div>
                        @error('slip')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <div class="sf-actions">
                <div class="sf-actions__meta">Le depot sera visible dans la liste avec le statut En attente.</div>
                <div class="toolbar-actions">
                    <a class="btn btn-ghost" href="{{ route('deposits.index', request()->only('center_id')) }}">Annuler</a>
                    <button class="btn btn-primary" type="submit">Enregistrer le depot</button>
                </div>
            </div>
        </form>
    </section>

    <aside class="sf-sidebar">
        <section class="sf-card sf-card--side sf-sidebox">
            <div class="sf-sidebox__title">Pieces a verifier</div>
            <div class="sf-checks">
                <div class="sf-check"><span class="sf-check__dot"></span><span>Le montant du bordereau doit correspondre au depot saisi.</span></div>
                <div class="sf-check"><span class="sf-check__dot"></span><span>La session est facultative, mais utile pour le suivi.</span></div>
                <div class="sf-check"><span class="sf-check__dot"></span><span>Le bordereau reste consultable apres l'enregistrement.</span></div>
            </div>
        </section>

        <section class="sf-card sf-card--side sf-sidebox">
            <div class="sf-sidebox__title">Resume</div>
            <div class="sf-summary">
                <div class="sf-summary__row"><span>Centre preselectionne</span><span class="sf-summary__value">{{ optional($accessibleCenters->firstWhere('id', $selectedCenterId))->name ?: 'A definir' }}</span></div>
                <div class="sf-summary__row"><span>Sessions ouvertes</span><span class="sf-summary__value">{{ $sessions->count() }}</span></div>
            </div>
        </section>
    </aside>
</div>@endsection



