@extends('layouts.app')

@section('page_title', 'Modifier utilisateur')
@section('page_kicker', 'Systeme')
@section('page_subtitle', 'Modifiez les informations, le role et les centres autorises de ' . $managedUser->name)

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
    .user-info-box {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: linear-gradient(135deg, #f8faff, #eef3fb);
        border-radius: 12px;
        margin-bottom: 20px;
    }
    .user-info-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--navy), var(--navy-mid));
        display: grid;
        place-items: center;
        color: white;
        font-size: 24px;
        font-weight: 700;
    }
    .user-info-details h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 4px;
    }
    .user-info-details p {
        font-size: 13px;
        color: var(--muted);
    }
</style>
@endpush

@section('content')
<div class="page-shell theme-page theme-ink">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Modification de compte</div>
            <div class="page-intro-text">Modifiez les informations de {{ $managedUser->name }}. Le mot de passe ne sera change que si vous en saisissez un nouveau.</div>
        </div>
    </section>

    <div class="form-shell wide">
        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <div class="table-card-title">Informations du compte</div>
                    <div class="td-muted">Modifiez les champs necessaires. Laissez le mot de passe vide pour le conserver.</div>
                </div>
            </div>

            <div class="user-info-box card-section">
                <div class="user-info-avatar">
                    {{ strtoupper(substr($managedUser->name, 0, 2)) }}
                </div>
                <div class="user-info-details">
                    <h3>{{ $managedUser->name }}</h3>
                    <p>{{ ucfirst(str_replace('_', ' ', $managedUser->role)) }} | {{ $managedUser->email }}</p>
                    <p>Compte cree le {{ $managedUser->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('users.update', $managedUser) }}" class="card-section stack-grid" data-loading="true" data-loading-text="Mise a jour du compte en cours...">
                @csrf
                @method('PUT')

                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Identite</div>
                    <div class="form-grid">
                        <div class="field">
                            <label>Nom complet *</label>
                            <input type="text" name="name" value="{{ old('name', $managedUser->name) }}" placeholder="Jean Dupont" required>
                            @error('name')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>E-mail *</label>
                            <input type="email" name="email" value="{{ old('email', $managedUser->email) }}" placeholder="jean@exemple.cm" required>
                            @error('email')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="field" style="grid-column: 1 / -1;">
                            <label>Telephone</label>
                            <input type="text" name="phone" value="{{ old('phone', $managedUser->phone) }}" placeholder="+237 6XX XXX XXX">
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
                                    <option value="super_admin"  {{ old('role', $managedUser->role) === 'super_admin'  ? 'selected' : '' }}>Super admin</option>
                                    <option value="center_admin" {{ old('role', $managedUser->role) === 'center_admin' ? 'selected' : '' }}>Directeur de centre</option>
                                    <option value="secretary"    {{ old('role', $managedUser->role) === 'secretary'    ? 'selected' : '' }}>Secretaire</option>
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
                                    <option value="{{ $center->id }}" {{ old('center_id', $managedUser->center_id) == $center->id ? 'selected' : '' }}>
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
                            @php
                                $userCenterIds = old('center_ids', $managedUser->centers->pluck('id')->toArray());
                            @endphp
                            @foreach ($centers as $center)
                                <label style="display: flex; align-items: center; gap: 10px;">
                                    <input type="checkbox" name="center_ids[]" value="{{ $center->id }}"
                                        {{ in_array($center->id, $userCenterIds) ? 'checked' : '' }}>
                                    <span>{{ $center->name }} - {{ $center->city }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('center_ids')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="surface-muted">
                    <div class="section-label" style="margin-bottom: 10px;">Securite (optionnel)</div>
                    <div class="form-grid">
                        <div class="field">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="password" placeholder="Laissez vide pour ne pas changer">
                            @error('password')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>Confirmation</label>
                            <input type="password" name="password_confirmation" placeholder="Confirmez le nouveau mot de passe">
                        </div>
                    </div>
                    <p style="font-size: 12px; color: var(--muted); margin-top: 8px;">* Le mot de passe ne sera modifie que si vous saisissez une valeur.</p>
                </div>

                <div class="toolbar-actions">
                    <a class="btn btn-ghost" href="{{ route('users.index') }}">Annuler</a>
                    <button class="btn btn-primary" type="submit">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



