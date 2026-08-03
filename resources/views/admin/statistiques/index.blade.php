@extends('layouts.app')

@section('title', 'Statistiques détaillées')

@section('page-title', 'Statistiques détaillées')

@section('content')

<div class="dash">

<style>
    .dash { animation: dashFadeIn .4s ease both; }
    @keyframes dashFadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    .dash .kpi-card {
        background: #fff;
        border: 1px solid #eef0f2;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .dash .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
    }

    .dash .kpi-icon {
        width: 54px;
        height: 54px;
        min-width: 54px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #fff;
    }
    .dash .kpi-icon.ic-primary { background: #0d6efd; }
    .dash .kpi-icon.ic-success { background: #198754; }
    .dash .kpi-icon.ic-warning { background: #fd7e14; }
    .dash .kpi-icon.ic-danger  { background: #dc3545; }

    .dash .kpi-label { font-size: .85rem; color: #6c757d; margin-bottom: .1rem; }
    .dash .kpi-value { font-size: 1.6rem; font-weight: 700; color: #1a1f2b; line-height: 1.2; }

    .dash .panel {
        background: #fff;
        border: 1px solid #eef0f2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        height: 100%;
    }
    .dash .panel-header {
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: #1a1f2b;
        border-bottom: 1px solid #f1f2f4;
    }
    .dash .panel-body { padding: 1.25rem; }

    .dash .mini-stats {
        font-size: .82rem;
        color: #8a94a6;
        margin-top: 1rem;
        padding-top: .85rem;
        border-top: 1px solid #f1f2f4;
    }
    .dash .mini-stats strong { color: #1a1f2b; }
</style>


<!-- Cartes KPI -->

<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-primary"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="kpi-label">Produits</div>
                <div class="kpi-value">{{ $stats['total_produits'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-success"><i class="bi bi-stack"></i></div>
            <div>
                <div class="kpi-label">Stock total</div>
                <div class="kpi-value">{{ $stats['stock_total'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-warning"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="kpi-label">Valeur du stock</div>
                <div class="kpi-value">{{ number_format($stats['valeur_stock'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-danger"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="kpi-label">Stock bas (&le; {{ $seuil }})</div>
                <div class="kpi-value">{{ $stats['produits_stock_bas'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon" style="background:#6f42c1;"><i class="bi bi-basket-fill"></i></div>
            <div>
                <div class="kpi-label">Valeur matières premières</div>
                <div class="kpi-value">{{ number_format($stats['valeur_matieres_premieres'], 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

</div>


<!-- Résumé mouvements + Top produits -->

<div class="row g-4 mb-4">

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Résumé des mouvements</div>
            <div class="panel-body">
                <canvas id="chartMouvements" height="200"></canvas>
                <p class="mini-stats mb-0">
                    Catégories : <strong>{{ $stats['total_categories'] }}</strong>
                    — Fournisseurs : <strong>{{ $stats['total_fournisseurs'] }}</strong>
                    — Utilisateurs : <strong>{{ $stats['total_utilisateurs'] }}</strong>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Top 5 produits (par quantité en stock)</div>
            <div class="panel-body">
                @if($topProduits->isEmpty())
                    <p class="text-muted mb-0">Aucun produit enregistré.</p>
                @else
                    <canvas id="chartTopProduits" height="200"></canvas>
                @endif
            </div>
        </div>
    </div>

</div>


<!-- Stock bas + Répartition catégories -->

<div class="row g-4">

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Produits en stock bas</div>
            <div class="panel-body">
                @if($produitsStockBas->isEmpty())
                    <p class="text-muted mb-0">Aucun produit en stock bas actuellement.</p>
                @else
                    <canvas id="chartStockBas" height="200"></canvas>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Répartition du stock par catégorie</div>
            <div class="panel-body">
                @if($repartitionCategories->isEmpty())
                    <p class="text-muted mb-0">Aucune donnée disponible.</p>
                @else
                    <canvas id="chartCategories" height="200"></canvas>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Matières premières -->

<div class="row g-4 mt-1">

    <div class="col-md-12">
        <div class="panel">
            <div class="panel-header">Matières premières (quantité en stock)</div>
            <div class="panel-body">
                @if($matieresPremieres->isEmpty())
                    <p class="text-muted mb-0">Aucune matière première enregistrée.</p>
                @else
                    <canvas id="chartMatieresPremieres" height="180"></canvas>
                @endif
            </div>
        </div>
    </div>

</div>

</div>

@endsection


@push('scripts')

<script src="{{ asset('js/chart.umd.min.js') }}"></script>

<script>

    // Résumé des mouvements (entrées vs sorties)
    new Chart(document.getElementById('chartMouvements'), {
        type: 'bar',
        data: {
            labels: ['Entrées', 'Sorties'],
            datasets: [{
                label: 'Quantité totale',
                data: [{{ $stats['quantite_entrees'] }}, {{ $stats['quantite_sorties'] }}],
                backgroundColor: ['#198754', '#dc3545'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    @if($topProduits->isNotEmpty())
    new Chart(document.getElementById('chartTopProduits'), {
        type: 'bar',
        data: {
            labels: @json($topProduits->pluck('nom')),
            datasets: [{
                label: 'Quantité en stock',
                data: @json($topProduits->pluck('quantite')),
                backgroundColor: '#0d6efd',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    @endif

    @if($produitsStockBas->isNotEmpty())
    new Chart(document.getElementById('chartStockBas'), {
        type: 'bar',
        data: {
            labels: @json($produitsStockBas->pluck('nom')),
            datasets: [{
                label: 'Quantité restante',
                data: @json($produitsStockBas->pluck('quantite')),
                backgroundColor: '#dc3545',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    @endif

    @if($repartitionCategories->isNotEmpty())
    new Chart(document.getElementById('chartCategories'), {
        type: 'doughnut',
        data: {
            labels: @json($repartitionCategories->pluck('nom')),
            datasets: [{
                data: @json($repartitionCategories->pluck('produits_sum_quantite')),
                backgroundColor: ['#0d6efd', '#198754', '#fd7e14', '#dc3545', '#6f42c1', '#20c997'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '62%',
        }
    });
    @endif

    @if($matieresPremieres->isNotEmpty())
    new Chart(document.getElementById('chartMatieresPremieres'), {
        type: 'bar',
        data: {
            labels: @json($matieresPremieres->pluck('nom')),
            datasets: [{
                label: 'Quantité en stock',
                data: @json($matieresPremieres->pluck('quantite')),
                backgroundColor: '#6f42c1',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    @endif

</script>

@endpush