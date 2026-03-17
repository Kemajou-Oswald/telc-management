@extends('layouts.app')

@section('page_title', 'Etudiants')
@section('page_kicker', 'Operations')
@section('page_subtitle', 'Cette page sert au dossier candidat. Les inscriptions, paiements et recus restent dans la page Inscriptions.')

@section('page_actions')
    @if ($user->isSecretary() || $user->isCenterAdmin())
        <a class="btn btn-primary" href="{{ route('registrations.create', request()->only('center_id')) }}">Nouvelle inscription</a>
    @endif
@endsection

@section('content')
<div class="page-shell stack-grid theme-page theme-emerald">
    <section class="page-intro">
        <div class="page-intro-copy">
            <div class="page-intro-title">Base candidats</div>
            <div class="page-intro-text">Les etudiants regroupent l identite du candidat. La page Inscriptions suit plutot les passages en session et les paiements.</div>
        </div>
        <div class="actions-inline">
            <span class="badge neutral">{{ $students->total() }} dossier{{ $students->total() > 1 ? 's' : '' }}</span>
            @if (request('session_id'))<span class="badge success">Session filtree</span>@endif
            @if (request('center_id'))<span class="badge warning">Centre filtre</span>@endif
        </div>
    </section>

    <section class="data-panel">
        <div class="table-card-header"><div class="table-card-title">Filtres</div></div>
        <form method="GET" class="card-section">
            <div class="toolbar-grid">
                <div class="search-wrap">
                    <span class="search-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
                    <input class="search-input" type="text" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail, ville...">
                </div>
                @if ($centerOptions->isNotEmpty())
                    <div class="field"><label>Centre</label><select name="center_id"><option value="">Tous les centres</option>@foreach ($centerOptions as $center)<option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>@endforeach</select></div>
                @endif
                <div class="field"><label>Session</label><select name="session_id"><option value="">Toutes les sessions</option>@foreach ($sessionOptions as $session)<option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>{{ $session->title }} - {{ optional($session->center)->name }}</option>@endforeach</select></div>
                <div class="field"><label>Niveau</label><select name="level"><option value="">Tous niveaux</option>@foreach (\App\Models\ExamSession::LEVELS as $levelOption)<option value="{{ $levelOption }}" {{ request('level') === $levelOption ? 'selected' : '' }}>{{ $levelOption }}</option>@endforeach</select></div>
                <div class="field"><label>Paiement</label><select name="payment_status"><option value="">Tous</option><option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>En attente</option><option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paye</option></select></div>
            </div>
            <div class="toolbar-actions">
                @if (request()->hasAny(['q', 'center_id', 'session_id', 'level', 'payment_status']))
                    <a class="btn btn-ghost" href="{{ route('students.index') }}">Reinitialiser</a>
                @endif
                <button class="btn btn-secondary" type="submit">Appliquer</button>
            </div>
        </form>
    </section>

    <section class="table-card">
        <div class="table-card-header"><div><div class="table-card-title">Liste des candidats</div><div class="td-muted">Ici vous suivez le dossier du candidat, pas le detail transactionnel de chaque inscription.</div></div></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Candidat</th><th>Naissance</th><th>Langue / sexe</th><th>Habitation</th><th>Centre</th><th>Historique</th></tr></thead>
                <tbody>
                    @forelse ($students as $student)
                        @php
                            $latestRegistration = $student->registrations->sortByDesc('registered_at')->first();
                        @endphp
                        <tr>
                            <td><strong>{{ $student->full_name }}</strong><div class="td-muted">{{ $student->email ?: 'E-mail non renseigne' }}</div></td>
                            <td class="td-muted">{{ optional($student->date_of_birth)->format('d/m/Y') ?: 'Non renseignee' }}<div>{{ $student->place_of_birth ?: 'Lieu non renseigne' }}</div></td>
                            <td class="td-muted">{{ $student->language_label ?: 'Langue non renseignee' }}<div>{{ $student->gender_label ?: 'Sexe non renseigne' }}</div></td>
                            <td class="td-muted">{{ $student->city ?: 'Ville N/A' }} - {{ $student->country ?: 'Pays N/A' }}</td>
                            <td class="td-muted">{{ optional($student->center)->name ?: 'Aucun centre' }}</td>
                            <td>
                                <span class="badge neutral">{{ $student->active_registrations_count }} inscription{{ $student->active_registrations_count > 1 ? 's' : '' }}</span>
                                <div class="td-muted" style="margin-top: 6px;">{{ optional(optional($latestRegistration)->examSession)->title ?: 'Aucune session recente' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><div class="empty-state-title">Aucun etudiant enregistre</div><div class="empty-state-desc">Les candidats apparaitront ici apres leur premiere inscription.</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            <div class="pagination">{{ $students->links() }}</div>
        @endif
    </section>
</div>
@endsection



