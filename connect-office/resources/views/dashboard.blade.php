@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

<div class="mb-12">
    <h1 class="text-4xl font-semibold text-gray-800 dark:text-white">
        Tableau de bord
    </h1>
    <p class="mt-3 text-gray-500 dark:text-gray-400 max-w-2xl">
        Suivez l’évolution de l’activité et les indicateurs clés.
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-14">

    <div class="card p-6">
        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Produits</div>
        <div class="text-4xl font-semibold text-gray-900 dark:text-white">
            {{ $totalProducts }}
        </div>
    </div>

    <div class="card p-6">
        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Catégories</div>
        <div class="text-4xl font-semibold text-gray-900 dark:text-white">
            {{ $totalCategories }}
        </div>
    </div>

    <div class="card p-6">
        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Total Devis</div>
        <div class="text-4xl font-semibold text-gray-900 dark:text-white">
            {{ $totalQuotes }}
        </div>
    </div>

    <div class="card p-6">
        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Nouveaux Devis</div>
        <div class="text-4xl font-semibold text-red-500">
            {{ $newQuotes }}
        </div>
    </div>

</div>

<div class="card p-8">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">
        Évolution des devis
    </h2>
    <canvas id="quotesChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('quotesChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($quotesPerMonth->pluck('month')) !!},
        datasets: [{
            label: 'Nombre de devis',
            data: {!! json_encode($quotesPerMonth->pluck('total')) !!},
            borderColor: '#1E98C5',
            backgroundColor: 'rgba(30,152,197,0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

@endsection
