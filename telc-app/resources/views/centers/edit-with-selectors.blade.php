@extends('layouts.app')

@section('page_title', 'Modifier un centre')
@section('page_kicker', 'Pilotage')
@section('page_subtitle', 'Modifiez les informations du centre.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('centers.index') }}">Retour</a>
@endsection

@section('content')
<div class="page-shell stack-grid theme-page theme-sky">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Modifier le centre</div>
            <div class="page-intro-text">Mettez à jour les informations du centre {{ $center->name }}.</div>
        </div>
    </section>

    <section class="form-card">
        <div class="form-card-header">
            <div class="form-card-title">Informations du centre</div>
            <div class="td-muted">Tous les champs marqués d'un astérisque (*) sont obligatoires.</div>
        </div>

        <form method="POST" action="{{ route('centers.update', $center) }}" class="form-stack" data-loading="true" data-loading-text="Mise a jour du centre en cours...">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-col">
                    <label for="name" class="form-label">Nom du centre *</label>
                    <input type="text" id="name" name="name" value="{{ $center->name }}" class="form-control" required>
                </div>
                <div class="form-col">
                    <label for="code" class="form-label">Code du centre *</label>
                    <input type="text" id="code" name="code" value="{{ $center->code }}" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="city" class="form-label">Ville *</label>
                    <select id="city" name="city" class="form-control" required>
                        <option value="">Sélectionner une ville...</option>
                        @foreach (App\Models\Center::getValidCities() as $key => $city)
                            <option value="{{ $key }}" {{ $center->city === $key ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-col">
                    <label for="country" class="form-label">Pays *</label>
                    <select id="country" name="country" class="form-control" required>
                        <option value="">Sélectionner un pays...</option>
                        @foreach (App\Models\Center::getValidCountries() as $code => $country)
                            <option value="{{ $code }}" {{ $center->country === $code ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ $center->email }}" class="form-control">
                </div>
                <div class="form-col">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ $center->phone }}" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="address" class="form-label">Adresse</label>
                    <textarea id="address" name="address" class="form-control" rows="3">{{ $center->address }}</textarea>
                </div>
                <div class="form-col">
                    <label for="is_active" class="form-label">Statut</label>
                    <select id="is_active" name="is_active" class="form-control">
                        <option value="1" {{ $center->is_active ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ !$center->is_active ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('centers.index') }}" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </section>
</div>
@endsection

