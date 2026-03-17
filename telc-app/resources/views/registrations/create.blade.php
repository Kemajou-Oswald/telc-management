@extends('layouts.app')

@section('page_title', 'Nouvelle inscription')
@section('page_kicker', 'Operations')
@section('page_subtitle', 'Choisissez la session et renseignez les informations du candidat.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('registrations.index', request()->only('center_id')) }}">Retour</a>
@endsection

@include('partials.soft-form-styles')

@section('content')
<div class="sf-page">
    <section class="sf-card sf-card--main">
        <div class="sf-hero">
            <span class="sf-hero__eyebrow">Nouvelle inscription</span>
            <h2 class="sf-hero__title">Enregistrer un candidat</h2>
            <p class="sf-hero__text">Le montant, le recu et le centre de rattachement seront derives de la session choisie.</p>
            <div class="sf-hero__meta">
                <span class="sf-pill">{{ $sessions->count() }} session{{ $sessions->count() > 1 ? 's' : '' }} ouverte{{ $sessions->count() > 1 ? 's' : '' }}</span>
                <span class="sf-pill">{{ $accessibleCenters->count() }} centre{{ $accessibleCenters->count() > 1 ? 's' : '' }} disponible{{ $accessibleCenters->count() > 1 ? 's' : '' }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('registrations.store') }}" class="sf-form" data-loading="true" data-loading-text="Enregistrement en cours...">
            @csrf

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Centre et session</div>
                        <div class="sf-section__text">Le centre doit correspondre a la session retenue.</div>
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
                        <label for="exam_session_id">Session ouverte</label>
                        <select id="exam_session_id" name="exam_session_id" required>
                            <option value="">Selectionner une session</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" {{ (string) old('exam_session_id') === (string) $session->id ? 'selected' : '' }}>{{ $session->title }} - {{ optional($session->center)->name }} - {{ number_format($session->price, 0, ',', ' ') }} FCFA</option>
                            @endforeach
                        </select>
                        @error('exam_session_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Identite</div>
                        <div class="sf-section__text">Les informations servent a creer ou rattacher le dossier etudiant.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field">
                        <label for="last_name">Nom</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="DUPONT" required>
                        @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="first_name">Prenom</label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Jean" required>
                        @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="email">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="etudiant@exemple.com">
                        @error('email')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="gender">Sexe</label>
                        <select id="gender" name="gender">
                            <option value="">Selectionner</option>
                            <option value="M" {{ old('gender') === 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ old('gender') === 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="date_of_birth">Date de naissance</label>
                        <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="place_of_birth">Lieu de naissance</label>
                        <input id="place_of_birth" type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" placeholder="Douala">
                        @error('place_of_birth')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Localisation</div>
                        <div class="sf-section__text">Renseignez les informations utiles pour l'export et l'identification du candidat.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field">
                        <label for="city">Ville</label>
                        <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="Douala">
                        @error('city')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="country">Pays</label>
                        <input id="country" type="text" name="country" value="{{ old('country', 'CM - Cameroon') }}" required>
                        @error('country')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field field--full">
                        <label for="base_language">Langue de base</label>
                        <select id="base_language" name="base_language">
                            <option value="">Selectionner</option>
                            <option value="English" {{ \App\Models\Student::normalizeLanguage(old('base_language')) === 'English' ? 'selected' : '' }}>002-English/English</option>
                            <option value="French" {{ \App\Models\Student::normalizeLanguage(old('base_language')) === 'French' ? 'selected' : '' }}>003-French/Französisch</option>
                        </select>
                        @error('base_language')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <div class="sf-actions">
                <div class="sf-actions__meta">Le recu et le montant seront generes automatiquement apres validation.</div>
                <div class="toolbar-actions">
                    <a class="btn btn-ghost" href="{{ route('registrations.index', request()->only('center_id')) }}">Annuler</a>
                    <button class="btn btn-primary" type="submit">Enregistrer l'inscription</button>
                </div>
            </div>
        </form>
    </section>

    <aside class="sf-sidebar">
        <section class="sf-card sf-card--side sf-sidebox">
            <div class="sf-sidebox__title">Avant de valider</div>
            <div class="sf-sidebox__text">Verifiez surtout la session, l'identite et la date de naissance.</div>
            <div class="sf-checks">
                <div class="sf-check"><span class="sf-check__dot"></span><span>Le candidat ne peut pas etre inscrit deux fois sur la meme session active.</span></div>
                <div class="sf-check"><span class="sf-check__dot"></span><span>Le paiement reste en attente tant qu'il n'a pas ete marque comme recu.</span></div>
                <div class="sf-check"><span class="sf-check__dot"></span><span>Le recu sera disponible apres paiement.</span></div>
            </div>
        </section>

        <section class="sf-card sf-card--side sf-sidebox">
            <div class="sf-sidebox__title">Resume</div>
            <div class="sf-summary">
                <div class="sf-summary__row"><span>Sessions ouvertes</span><span class="sf-summary__value">{{ $sessions->count() }}</span></div>
                <div class="sf-summary__row"><span>Centre preselectionne</span><span class="sf-summary__value">{{ optional($accessibleCenters->firstWhere('id', $selectedCenterId))->name ?: 'A definir' }}</span></div>
            </div>
        </section>
    </aside>
</div>
@endsection



