@extends('layouts.app')

@section('page_title', 'Nouvelle inscription')
@section('page_kicker', 'Inscriptions')
@section('page_subtitle', 'Enregistrez un nouvel étudiant pour une session d\'examen.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('registrations.index') }}">Retour</a>
@endsection

@section('content')
<div class="page-shell stack-grid theme-page theme-sky">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Nouvelle inscription</div>
            <div class="page-intro-text">Remplissez les informations pour inscrire un étudiant à une session d'examen.</div>
        </div>
    </section>

    <section class="form-card">
        <div class="form-card-header">
            <div class="form-card-title">Informations de l'étudiant</div>
            <div class="td-muted">Tous les champs marqués d'un astérisque (*) sont obligatoires.</div>
        </div>

        <form method="POST" action="{{ route('registrations.store') }}" class="form-stack" data-loading="true" data-loading-text="Enregistrement en cours...">
            @csrf

            <!-- Informations personnelles -->
            <div class="form-section">
                <h3 class="form-section-title">Informations personnelles</h3>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="first_name" class="form-label">Prénom *</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-col">
                        <label for="last_name" class="form-label">Nom *</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    <div class="form-col">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label for="date_of_birth" class="form-label">Date de naissance</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control">
                    </div>
                    <div class="form-col">
                        <label for="place_of_birth" class="form-label">Lieu de naissance</label>
                        <input type="text" id="place_of_birth" name="place_of_birth" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <label for="gender" class="form-label">Genre</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="">Sélectionner...</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="form-col">
                        <label for="base_language" class="form-label">Langue maternelle</label>
                        <select id="base_language" name="base_language" class="form-control">
                            <option value="">Sélectionner...</option>
                            <option value="French">Français</option>
                            <option value="English">English</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="form-section">
                <h3 class="form-section-title">Adresse</h3>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="city" class="form-label">Ville *</label>
                        <select id="city" name="city" class="form-control" required>
                            <option value="">Sélectionner une ville...</option>
                            @foreach (App\Models\Student::getValidCities() as $key => $city)
                                <option value="{{ $key }}">{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-col">
                        <label for="country" class="form-label">Pays *</label>
                        <select id="country" name="country" class="form-control" required>
                            <option value="">Sélectionner un pays...</option>
                            @foreach (App\Models\Student::getValidCountries() as $code => $country)
                                <option value="{{ $code }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Session d'examen -->
            <div class="form-section">
                <h3 class="form-section-title">Session d'examen</h3>
                
                <div class="form-row">
                    <div class="form-col">
                        <label for="exam_session_id" class="form-label">Session d'examen *</label>
                        <select id="exam_session_id" name="exam_session_id" class="form-control" required>
                            <option value="">Sélectionner une session...</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}">
                                    {{ $session->title }} - {{ $session->level }} - {{ $session->center->name }} ({{ $session->city }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-col">
                        <label for="payment_status" class="form-label">Statut de paiement *</label>
                        <select id="payment_status" name="payment_status" class="form-control" required>
                            <option value="pending">En attente</option>
                            <option value="paid">Payé</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('registrations.index') }}" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">Créer l'inscription</button>
            </div>
        </form>
    </section>
</div>
@endsection

