@extends('layouts.app')

@section('page_title', 'Creer un utilisateur')
@section('page_kicker', 'Systeme')
@section('page_subtitle', 'Affectez un directeur ou une secretaire a un ou plusieurs centres, avec un centre principal pour les operations courantes.')

@section('page_actions')
    <a class="btn btn-ghost" href="{{ route('users.index') }}">Retour a la liste</a>
@endsection

@push('styles')
<style>
    .section-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #7a86a2;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-shell theme-page theme-ink">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Nouveau compte utilisateur</div>
            <div class="page-intro-text">Creez un compte pour un directeur de centre ou une secretaire et affectez-le aux centres appropries.</div>
        </div>
    </section>

    <div class="form-shell wide">
        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <div class="table-card-title">Informations du compte</div>
                    <div class="td-muted">Remplissez tous les champs obligatoires marques par *.</div>
                </div>
            </div>
            <form method="POST" action="{{ route('users.store') }}" class="card-section stack-grid" data-loading="true" data-loading-text="Creation du compte en cours...">
                @csrf

                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Identite</div>
                    <div class="form-grid">
                        <div class="field">
                            <label>Nom complet *</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Jean Dupont" required>
                            @error('name')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>E-mail *</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="jean@exemple.cm" required>
                            @error('email')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="field" style="grid-column: 1 / -1;">
                            <label>Telephone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+237 6XX XXX XXX">
                        </div>
                    </div>
                </div>

                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Affectation</div>
                    <div class="form-grid">
                        @if ($user->isCenterAdmin())
                            <input type="hidden" name="role" value="secretary">
                            <div class="field">
                                <label>Role *</label>
                                <input type="text" value="Secretaire" disabled>
                            </div>
                        @else
                            <div class="field">
                                <label>Role *</label>
                                <select name="role" required>
                                    <option value="">Selectionnez un role</option>
                                    <option value="super_admin"  {{ old('role') === 'super_admin'  ? 'selected' : '' }}>Super admin</option>
                                    <option value="center_admin" {{ old('role') === 'center_admin' ? 'selected' : '' }}>Directeur de centre</option>
                                    <option value="secretary"    {{ old('role') === 'secretary'    ? 'selected' : '' }}>Secretaire</option>
                                </select>
                                @error('role')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        @endif
                        <div class="field">
                            <label>Centre principal</label>
                            <select name="center_id">
                                @if (! $user->isCenterAdmin())
                                <option value="">Aucun centre - portee globale</option>
                            @endif
                                @foreach ($centers as $center)
                                    <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>
                                        {{ $center->name }} - {{ $center->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('center_id')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="field" style="margin-top: 16px;">
                        <label>Centres autorises *</label>
                        <div class="surface-muted" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px; margin-top: 8px;">
                            @foreach ($centers as $center)
                                <label style="display: flex; align-items: center; gap: 10px;">
                                    <input type="checkbox" name="center_ids[]" value="{{ $center->id }}"
                                        {{ collect(old('center_ids', []))->contains($center->id) ? 'checked' : '' }}>
                                    <span>{{ $center->name }} - {{ $center->city }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('center_ids')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Securite</div>
                    <div class="form-grid">
                        <div class="field">
                            <label>Mot de passe *</label>
                            <input type="password" name="password" placeholder="Minimum 8 caracteres" required>
                            @error('password')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>Confirmation *</label>
                            <input type="password" name="password_confirmation" placeholder="Repetez le mot de passe" required>
                        </div>
                    </div>
                </div>

                <div class="toolbar-actions">
                    <a class="btn btn-ghost" href="{{ route('users.index') }}">Annuler</a>
                    <button class="btn btn-primary" type="submit">Creer l utilisateur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



