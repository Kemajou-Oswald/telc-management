@extends('layouts.app')

@section('page_title', 'Tableau de bord')
@section('page_kicker', 'Dashboard Simple')
@section('page_subtitle', "Interface simplifiée.")

@section('page_actions')
    <a href="{{ route('registrations.export') }}" class="btn btn-secondary">Rapports &amp; export</a>
    <a href="{{ route('users.index') }}" class="btn btn-primary">Utilisateurs</a>
@endsection

<div class="dashboard-simple">
    <div class="welcome-section">
        <h1>Bienvenue, {{ $user->name }} !</h1>
        <p>Vous êtes connecté en tant que <strong>{{ $user->role }}</strong></p>
        
        @if(isset($error))
            <div class="error-message">
                <h3>Erreur détectée:</h3>
                <p>{{ $error }}</p>
            </div>
        @endif
    </div>

    <div class="actions-grid">
        <div class="action-card">
            <div class="card-icon">👥</div>
            <h3>Gérer les utilisateurs</h3>
            <p>Ajouter et modifier les comptes utilisateurs</p>
            <a href="{{ route('users.index') }}" class="btn">Voir les utilisateurs</a>
        </div>

        <div class="action-card">
            <div class="card-icon">🏢</div>
            <h3>Gérer les centres</h3>
            <p>Configurer les centres d'examen</p>
            <a href="{{ route('centers.index') }}" class="btn">Voir les centres</a>
        </div>

        <div class="action-card">
            <div class="card-icon">📝</div>
            <h3>Nouvelle inscription</h3>
            <p>Enregistrer un nouvel étudiant</p>
            <a href="{{ route('registrations.create') }}" class="btn">Créer une inscription</a>
        </div>

        <div class="action-card">
            <div class="card-icon">📊</div>
            <h3>Rapports et exports</h3>
            <p>Télécharger les données au format TELC</p>
            <a href="{{ route('registrations.export') }}" class="btn">Voir les rapports</a>
        </div>
    </div>

    <div class="stats-section">
        <h2>Statistiques simplifiées</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">0</div>
                <div class="stat-label">Étudiants</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">0</div>
                <div class="stat-label">Sessions</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">0</div>
                <div class="stat-label">Inscriptions</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">0</div>
                <div class="stat-label">Payées</div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-simple {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.welcome-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.welcome-section h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.error-message {
    background: #fee;
    border: 1px solid #fcc;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
    color: #c00;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.action-card {
    background: white;
    border: 1px solid #e1e1e1;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.action-card h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.action-card p {
    color: #666;
    margin-bottom: 1rem;
}

.action-card .btn {
    background: #4CAF50;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s;
}

.action-card .btn:hover {
    background: #45a049;
}

.stats-section {
    background: white;
    border: 1px solid #e1e1e1;
    border-radius: 12px;
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #4CAF50;
}

.stat-label {
    color: #666;
    margin-top: 0.5rem;
}
</style>
