@extends('layouts.app')

@section('page_title', 'Centres')
@section('page_kicker', 'Pilotage')
@section('page_subtitle')
    Consultez les centres, leur activite et leurs coordonnees.
@endsection

@section('page_actions')
    @if ($user->isSuperAdmin())
        <a class="btn btn-primary" href="{{ route('centers.create') }}">Nouveau centre</a>
    @endif
@endsection

@section('content')
@php
    $archived = $archived ?? false;
    $totalCenters = $totalCenters ?? $centers->count();
    $activeCenters = $activeCenters ?? $centers->where('is_active', true)->count();
    $archivedCenters = $archivedCenters ?? 0;
@endphp

<div class="page-shell stack-grid theme-page theme-sky">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Reseau des centres</div>
            <div class="page-intro-text">Retrouvez rapidement chaque centre, son volume de sessions et ses contacts.</div>
        </div>
        <div class="actions-inline">
            <span class="badge neutral">{{ $totalCenters }} centre{{ $totalCenters > 1 ? 's' : '' }}</span>
            <span class="badge success">{{ $activeCenters }} actif{{ $activeCenters > 1 ? 's' : '' }}</span>
            <span class="badge {{ $archivedCenters > 0 ? 'neutral' : 'success' }}">{{ $archivedCenters }} archive{{ $archivedCenters > 1 ? 's' : '' }}</span>
            @if ($user->isSuperAdmin())
                @if ($archived)
                    <a class="btn btn-ghost btn-sm" href="{{ route('centers.index') }}">Voir les actifs</a>
                @else
                    <a class="btn btn-ghost btn-sm" href="{{ route('centers.index', ['archived' => 1]) }}">Voir les archives</a>
                @endif
            @endif
        </div>
    </section>

    <section class="table-card">
        <div class="table-card-header">
            <div>
                <div class="table-card-title">Liste des centres</div>
                <div class="td-muted">Les responsables peuvent ensuite etre rattaches au bon centre.</div>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Centre</th>
                        <th>Code</th>
                        <th>Ville</th>
                        <th>Activite</th>
                        <th>Contact</th>
                        <th>Statut</th>
                        @if ($user->isSuperAdmin() || $user->isCenterAdmin())
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($centers as $center)
                        <tr>
                            <td>
                                <strong>{{ $center->name }}</strong>
                                <div class="td-muted">{{ $center->country }}</div>
                            </td>
                            <td class="td-mono td-muted">{{ $center->code }}</td>
                            <td class="td-muted">{{ $center->city ?: 'Non renseignee' }}</td>
                            <td>
                                <div class="td-muted">{{ $center->sessions_count }} session{{ $center->sessions_count > 1 ? 's' : '' }}</div>
                                <div class="td-muted">{{ $center->registrations_count }} inscription{{ $center->registrations_count > 1 ? 's' : '' }}</div>
                            </td>
                            <td class="td-muted">
                                {{ $center->email ?: 'E-mail non renseigne' }}<br>
                                {{ $center->phone ?: 'Telephone non renseigne' }}
                            </td>
                            <td>
                                @if ($center->trashed())
                                    <span class="badge neutral">Archive</span>
                                @else
                                    <span class="badge {{ $center->is_active ? 'success' : 'neutral' }}">{{ $center->is_active ? 'Actif' : 'Inactif' }}</span>
                                @endif
                            </td>
                            @if ($user->isSuperAdmin() || $user->isCenterAdmin())
                                <td>
                                    <div class="table-actions">
                                        @if ($center->trashed())
                                            @if ($user->isSuperAdmin())
                                                <form method="POST" action="{{ route('centers.archive', $center) }}" style="display:inline;" data-loading="true" data-loading-text="Restauration du centre en cours...">
                                                    @csrf
                                                    <input type="hidden" name="action" value="restore">
                                                    <button class="btn btn-ghost btn-sm" type="submit">Restaurer</button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('centers.edit', $center) }}" class="btn btn-ghost btn-sm">Modifier</a>
                                            @if ($user->isSuperAdmin())
                                                <a href="{{ route('centers.confirm-archive', $center) }}" class="btn btn-ghost btn-sm">Supprimer</a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($user->isSuperAdmin() || $user->isCenterAdmin()) ? 7 : 6 }}">
                                <div class="empty-state">
                                    <div class="empty-state-title">Aucun centre enregistre</div>
                                    <div class="empty-state-desc">Ajoutez un centre pour commencer la gestion.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection


