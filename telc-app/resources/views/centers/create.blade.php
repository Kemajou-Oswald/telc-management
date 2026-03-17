@extends('layouts.app')

@section('page_title', 'Nouveau centre')
@section('page_kicker', 'Pilotage')
@section('page_subtitle')
    Renseignez l'identit&eacute;, la localisation et le contact du centre.
@endsection

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('centers.index') }}">Retour aux centres</a>
@endsection

@php
    if (!function_exists('safeOld')) {
        function safeOld($key, $default = '') {
            $value = old($key, $default);
            return is_array($value) ? '' : $value;
        }
    }
@endphp
@include('partials.soft-form-styles')

@section('content')
<div class="sf-page" style="grid-template-columns:minmax(0,1fr);">
    <section class="sf-card sf-card--main">
        <div class="sf-hero">
            <span class="sf-hero__eyebrow">Nouveau centre</span>
            <h2 class="sf-hero__title">Cr&eacute;er un centre</h2>
            <p class="sf-hero__text">Enregistrez le centre avec ses coordonn&eacute;es principales.</p>
        </div>

        <form method="POST" action="{{ route('centers.store') }}" class="sf-form" data-loading="true" data-loading-text="Enregistrement du centre en cours...">
            @csrf

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Informations</div>
                        <div class="sf-section__text">Nom, code et statut du centre.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field">
                        <label for="name">Nom du centre</label>
                        <input id="name" type="text" name="name" value="{{ safeOld('name') }}" placeholder="TARA Yaound&eacute;" required>
                        @error('name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="code">Code</label>
                        <input id="code" type="text" name="code" value="{{ safeOld('code') }}" placeholder="TARA-YDE" required>
                        @error('code')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field field--full">
                        <label for="is_active">Statut</label>
                        <select id="is_active" name="is_active" required>
                            <option value="1" {{ safeOld('is_active', '1') === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ safeOld('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('is_active')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Localisation</div>
                        <div class="sf-section__text">Ville et pays du centre.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field">
                        <label for="city">Ville</label>
                        <input id="city" type="text" name="city" value="{{ safeOld('city') }}" placeholder="Yaoundé" required>
                        @error('city')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="country">Pays</label>
                        <input id="country" type="text" name="country" value="{{ safeOld('country') }}" placeholder="Cameroun" required>
                        @error('country')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Adresse</div>
                        <div class="sf-section__text">Adresse du centre.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field field--full">
                        <label for="address">Adresse</label>
                        <textarea id="address" name="address" rows="4" placeholder="Rue, quartier, ville.">{{ safeOld('address') }}</textarea>
                        @error('address')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <section class="sf-section">
                <div class="sf-section__head">
                    <div>
                        <div class="sf-section__title">Contact</div>
                        <div class="sf-section__text">T&eacute;l&eacute;phone et e-mail du centre.</div>
                    </div>
                </div>
                <div class="sf-grid">
                    <div class="field">
                        <label for="phone">T&eacute;l&eacute;phone</label>
                        <input id="phone" type="text" name="phone" value="{{ safeOld('phone') }}" placeholder="+237 6XX XXX XXX">
                        @error('phone')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="email">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ safeOld('email') }}" placeholder="centre@exemple.cm">
                        @error('email')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            <div class="sf-actions">
                <div></div>
                <div class="toolbar-actions">
                    <a class="btn btn-ghost" href="{{ route('centers.index') }}">Annuler</a>
                    <button class="btn btn-primary" type="submit">Cr&eacute;er le centre</button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection


