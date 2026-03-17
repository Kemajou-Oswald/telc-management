@extends('layouts.app')

@section('page_title', 'Utilisateurs')
@section('page_kicker', 'Systeme')
@section('page_subtitle', 'Affectez un directeur ou une secretaire a un ou plusieurs centres, avec un centre principal pour les operations courantes.')

@section('page_actions')
    <a class="btn btn-primary" href="{{ route('users.create') }}">Creer un utilisateur</a>
@endsection

@push('styles')
<style>
    /* Avatar sizing fix */
    .user-line {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .user-line .user-avatar-chip {
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 8px;
        display: grid;
        place-items: center;
    }

    .user-line .user-avatar-chip img,
    .user-line .user-avatar-chip .user-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: inherit;
    }

    .user-line > img {
        flex-shrink: 0;
        object-fit: cover;
        display: block;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
@php
    $activeUsers = $activeUsers ?? $users->where('is_blocked', false)->count();
    $blockedUsers = $blockedUsers ?? $users->where('is_blocked', true)->count();
    $archivedUsers = $archivedUsers ?? 0;
    $totalUsers = $totalUsers ?? $users->count();
    $archived = $archived ?? false;
    $audits = $users->flatMap->audits->sortByDesc('created_at')->take(12);
@endphp

<div class="page-shell stack-grid theme-page theme-ink">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Administration des comptes</div>
            <div class="page-intro-text">Le formulaire reste hors de la liste principale pour garder une page de lecture claire, comme sur centres et sessions.</div>
        </div>
        <div class="actions-inline">
            <span class="badge neutral">{{ $totalUsers }} compte{{ $totalUsers > 1 ? 's' : '' }}</span>
            <span class="badge success">{{ $activeUsers }} actif{{ $activeUsers > 1 ? 's' : '' }}</span>
            <span class="badge danger">{{ $blockedUsers }} bloque{{ $blockedUsers > 1 ? 's' : '' }}</span>
            <span class="badge {{ $archivedUsers > 0 ? 'neutral' : 'success' }}">{{ $archivedUsers }} archive{{ $archivedUsers > 1 ? 's' : '' }}</span>
            @if ($user->isSuperAdmin())
                @if ($archived)
                    <a class="btn btn-ghost btn-sm" href="{{ route('users.index') }}">Voir les actifs</a>
                @else
                    <a class="btn btn-ghost btn-sm" href="{{ route('users.index', ['archived' => 1]) }}">Voir les archives</a>
                @endif
            @endif
        </div>
    </section>

    <section class="table-card">
        <div class="table-card-header">
            <div>
                <div class="table-card-title">Comptes existants</div>
                <div class="td-muted">Chaque utilisateur peut maintenant etre rattache a plusieurs centres, avec un centre principal.</div>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Role</th>
                        <th>Centre principal</th>
                        <th>Centres autorises</th>
                        <th>Telephone</th>
                        <th>Etat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $managedUser)
                        @php
                            $isSuperAdmin = $managedUser->role === 'super_admin';
                            $isSelf = $managedUser->id === $user->id;
                            $canManage = ($user->isSuperAdmin() && ! $isSuperAdmin && ! $isSelf) || ($user->isCenterAdmin() && $managedUser->role === 'secretary');
                        @endphp
                        <tr>
                            <td>
                                <div class="user-line">
                                    @include('partials.user-avatar', ['user' => $managedUser, 'size' => 32])
                                    <div>
                                        <strong>{{ $managedUser->name }}</strong>
                                        <div class="td-muted">{{ $managedUser->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-muted">
                                {{ $managedUser->role === 'super_admin'
                                    ? 'Super admin'
                                    : ($managedUser->role === 'center_admin' ? 'Directeur de centre' : 'Secretaire') }}
                            </td>
                            <td class="td-muted">{{ optional($managedUser->center)->name ?: 'Global' }}</td>
                            <td class="td-muted">{{ $managedUser->centers->pluck('name')->implode(', ') ?: 'Portee globale' }}</td>
                            <td class="td-muted">{{ $managedUser->phone ?: 'Non renseigne' }}</td>
                            <td>
                                @if ($managedUser->trashed())
                                    <span class="badge neutral">Archive</span>
                                @else
                                    <span class="badge {{ $managedUser->is_blocked ? 'danger' : 'success' }}">
                                        {{ $managedUser->is_blocked ? 'Bloque' : 'Actif' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    @if (! $managedUser->trashed())
                                        @if (! $isSuperAdmin || $isSelf)
                                            <a href="{{ route('users.edit', $managedUser) }}" class="btn btn-ghost btn-sm">Modifier</a>
                                        @endif
                                        @if ($canManage)
                                            <form method="POST" action="{{ route('users.toggle-block', $managedUser) }}" style="display:inline;" data-loading="true" data-loading-text="Mise a jour du statut en cours...">
                                                @csrf
                                                <button class="btn btn-{{ $managedUser->is_blocked ? 'primary' : 'danger' }} btn-sm" type="submit">
                                                    {{ $managedUser->is_blocked ? 'Debloquer' : 'Bloquer' }}
                                                </button>
                                            </form>
                                            @if ($user->isSuperAdmin())
                                                <form method="POST" action="{{ route('users.archive', $managedUser) }}" style="display:inline;" onsubmit="return confirm('Archiver ce compte ?');" data-loading="true" data-loading-text="Archivage du compte en cours...">
                                                    @csrf
                                                    <button class="btn btn-ghost btn-sm" type="submit">Archiver</button>
                                                </form>
                                            @endif
                                        @endif
                                        @if ($isSelf)
                                            <span class="badge neutral">Vous</span>
                                        @endif
                                    @else
                                        @if ($canManage && $user->isSuperAdmin())
                                            <form method="POST" action="{{ route('users.archive', $managedUser) }}" style="display:inline;" data-loading="true" data-loading-text="Restauration du compte en cours...">
                                                @csrf
                                                <input type="hidden" name="action" value="restore">
                                                <button class="btn btn-ghost btn-sm" type="submit">Restaurer</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-title">Aucun compte</div>
                                    <div class="empty-state-desc">Creez le premier utilisateur pour initialiser l exploitation.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($audits->isNotEmpty())
        <section class="table-card">
            <div class="table-card-header">
                <div>
                    <div class="table-card-title">Journal recent</div>
                    <div class="td-muted">Les dernieres actions sur les comptes sont visibles ici.</div>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Par</th>
                            <th>Date</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $audit)
                            <tr>
                                <td>
                                    <div class="user-line">
                                        @include('partials.user-avatar', ['user' => $audit->affectedUser, 'size' => 28])
                                        <strong>{{ optional($audit->affectedUser)->name ?: 'N/A' }}</strong>
                                    </div>
                                </td>
                                <td><span class="badge neutral">{{ $audit->action }}</span></td>
                                <td class="td-muted">
                                    <div class="user-line">
                                        @include('partials.user-avatar', ['user' => $audit->performer, 'size' => 24])
                                        <span>{{ optional($audit->performer)->name ?: 'Systeme' }}</span>
                                    </div>
                                </td>
                                <td class="td-muted td-mono">{{ optional($audit->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="td-muted">{{ $audit->details }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
</div>
@endsection




