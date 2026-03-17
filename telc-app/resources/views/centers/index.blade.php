@extends('layouts.app')

@section('page_title', 'Centres')
@section('page_kicker', 'Pilotage')
@section('page_subtitle')
    Consultez les centres, leur activité et leurs coordonnées.
@endsection

@section('page_actions')
    @if ($user->isSuperAdmin())
        <a class="btn btn-primary" href="{{ route('centers.create') }}">Nouveau centre</a>
    @endif
@endsection

@section('content')
<div class="page-shell stack-grid theme-page theme-sky">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">R&eacute;seau des centres</div>
            <div class="page-intro-text">Retrouvez rapidement chaque centre, son volume de sessions et ses contacts.</div>
        </div>
        <div class="actions-inline">
            <span class="badge neutral">{{ $centers->count() }} centre{{ $centers->count() > 1 ? 's' : '' }}</span>
            <span class="badge success">{{ $centers->where('is_active', true)->count() }} actif{{ $centers->where('is_active', true)->count() > 1 ? 's' : '' }}</span>
        </div>
    </section>

    <section class="table-card">
        <div class="table-card-header">
            <div>
                <div class="table-card-title">Liste des centres</div>
                <div class="td-muted">Les responsables peuvent ensuite être rattachés au bon centre.</div>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Centre</th>
                        <th>Code</th>
                        <th>Ville</th>
                        <th>Activité</th>
                        <th>Contact</th>
                        <th>Statut</th>
                        @if ($user->isSuperAdmin())
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
                            <td class="td-muted">{{ $center->city ?: 'Non renseignée' }}</td>
                            <td>
                                <div class="td-muted">{{ $center->sessions_count }} session{{ $center->sessions_count > 1 ? 's' : '' }}</div>
                                <div class="td-muted">{{ $center->registrations_count }} inscription{{ $center->registrations_count > 1 ? 's' : '' }}</div>
                            </td>
                            <td class="td-muted">
                                {{ $center->email ?: 'E-mail non renseigné' }}<br>
                                {{ $center->phone ?: 'Téléphone non renseigné' }}
                            </td>
                            <td><span class="badge {{ $center->is_active ? 'success' : 'neutral' }}">{{ $center->is_active ? 'Actif' : 'Inactif' }}</span></td>
                            @if ($user->isSuperAdmin())
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('centers.edit', $center) }}" class="btn btn-ghost btn-sm">Modifier</a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $user->isSuperAdmin() ? 7 : 6 }}">
                                <div class="empty-state">
                                    <div class="empty-state-title">Aucun centre enregistré</div>
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