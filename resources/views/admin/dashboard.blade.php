@extends('layouts.app')

@section('title', 'Dashboard Administrateur - Family')
@section('page-title', 'Tableau de bord Administrateur')

@section('content')
<div class="container-fluid px-0">

    <!-- En-tête : Salutation & Raccourcis -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Bonjour, {{ auth()->user()->name }} 👋</h3>
            @if($derniereConnexion)
                <p class="text-muted small mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span>Dernière connexion le {{ $derniereConnexion->created_at->format('d/m/Y à H:i') }}</span>
                    @if($derniereConnexion->ip_address)
                        <span class="badge bg-light text-secondary border">IP: {{ $derniereConnexion->ip_address }}</span>
                    @endif
                </p>
            @else
                <p class="text-muted small mb-0">Bienvenue sur votre espace de gestion globale.</p>
            @endif
        </div>

        <!-- Quick actions -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.produits.create') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Nouveau Produit</span>
            </a>
            <a href="{{ route('admin.entrees.create') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                <i class="bi bi-box-arrow-in-down"></i>
                <span>Entrée Stock</span>
            </a>
            <a href="{{ route('admin.sorties.create') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                <i class="bi bi-box-arrow-up"></i>
                <span>Sortie Stock</span>
            </a>
        </div>
    </div>

    <!-- Cartes KPI -->
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card accent-emerald">
                <div class="kpi-icon-box emerald">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div>
                    <div class="kpi-title">Stock total</div>
                    <div class="kpi-number">{{ number_format($stockTotal, 0, ',', ' ') }}</div>
                    <div class="kpi-subtext">Unités en entrepôt</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card accent-blue">
                <div class="kpi-icon-box blue">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </div>
                <div>
                    <div class="kpi-title">Entrées ce mois</div>
                    <div class="kpi-number">{{ number_format($entreesMois, 0, ',', ' ') }}</div>
                    <div class="kpi-subtext text-success d-flex align-items-center gap-1">
                        <i class="bi bi-graph-up-arrow"></i> Flux entrant
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card accent-amber">
                <div class="kpi-icon-box amber">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </div>
                <div>
                    <div class="kpi-title">Sorties ce mois</div>
                    <div class="kpi-number">{{ number_format($sortiesMois, 0, ',', ' ') }}</div>
                    <div class="kpi-subtext text-warning d-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-up"></i> Flux sortant
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card accent-rose">
                <div class="kpi-icon-box rose">
                    <i class="bi bi-exclamation-diamond-fill"></i>
                </div>
                <div>
                    <div class="kpi-title">Alertes stock bas</div>
                    <div class="kpi-number text-danger">{{ $alertesStockFaible }}</div>
                    <div class="kpi-subtext text-danger d-flex align-items-center gap-1">
                        <i class="bi bi-shield-exclamation"></i> Réapprovisionnement
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques : Mouvements & Répartition -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-bezier2 text-primary"></i>
                        <span>Évolution des flux (3 derniers mois)</span>
                    </div>
                    <span class="badge bg-light text-muted border">Mensuel</span>
                </div>
                <div class="card-body">
                    <div style="height: 270px; position: relative;">
                        <canvas id="chartMouvements"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-pie-chart-fill text-primary"></i>
                        <span>Répartition du stock par catégorie</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    @if($repartitionCategories->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-folder-x fs-1 opacity-50 d-block mb-2"></i>
                            Aucune catégorie disponible.
                        </div>
                    @else
                        <div class="row align-items-center">
                            <div class="col-6">
                                <div style="height: 190px; position: relative;">
                                    <canvas id="chartCategories"></canvas>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column gap-2" style="max-height: 200px; overflow-y: auto;">
                                    @php
                                        $colors = ['#059669', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];
                                    @endphp
                                    @foreach($repartitionCategories as $i => $categorie)
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $colors[$i % count($colors)] }}; flex-shrink: 0;"></span>
                                            <div class="overflow-hidden">
                                                <div class="small fw-bold text-dark text-truncate">{{ $categorie->nom }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    {{ number_format($categorie->produits_sum_quantite, 0, ',', ' ') }} ({{ $categorie->pourcentage }}%)
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux : Dernières entrées & Produits en alerte -->
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-down-left-square text-success"></i>
                        <span>Dernières entrées enregistrées</span>
                    </div>
                    <a href="{{ route('admin.entrees.index') }}" class="small text-decoration-none fw-semibold text-primary">
                        Voir tout <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($dernieresEntrees->isEmpty())
                        <p class="text-muted p-4 mb-0 text-center">Aucune entrée enregistrée récemment.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Date</th>
                                        <th>Produit</th>
                                        <th class="text-end pe-3">Quantité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dernieresEntrees as $entree)
                                        <tr>
                                            <td class="ps-3 small text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $entree->date_entree->format('d/m/Y') }}
                                            </td>
                                            <td class="fw-semibold text-dark">
                                                {{ $entree->produit->nom ?? '—' }}
                                            </td>
                                            <td class="text-end pe-3">
                                                <span class="badge badge-soft-success">
                                                    +{{ $entree->quantite }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle text-danger"></i>
                        <span>Stock faible / critique</span>
                    </div>
                    <a href="{{ route('admin.produits.index') }}" class="small text-decoration-none fw-semibold text-danger">
                        Gérer stock <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($produitsStockBas->isEmpty())
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-check-circle text-success fs-2 d-block mb-1"></i>
                            Aucun produit en stock critique !
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Produit</th>
                                        <th>Quantité</th>
                                        <th class="text-end pe-3">Niveau</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produitsStockBas as $produit)
                                        <tr>
                                            <td class="ps-3 fw-semibold text-dark">
                                                {{ $produit->nom }}
                                            </td>
                                            <td>
                                                <span class="fw-bold {{ $produit->critique ? 'text-danger' : 'text-warning' }}">
                                                    {{ $produit->quantite }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                @if($produit->critique)
                                                    <span class="badge badge-soft-danger">Critique</span>
                                                @else
                                                    <span class="badge badge-soft-warning">Faible</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Line chart for movements
        const ctxMouvements = document.getElementById('chartMouvements');
        if (ctxMouvements) {
            new Chart(ctxMouvements, {
                type: 'line',
                data: {
                    labels: @json($moisLabels),
                    datasets: [
                        {
                            label: 'Entrées',
                            data: @json($moisEntrees),
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#059669',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Sorties',
                            data: @json($moisSorties),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.05)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#ef4444',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            padding: 10,
                            boxPadding: 4,
                            usePointStyle: true,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '700' },
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // Doughnut chart for categories
        @if($repartitionCategories->isNotEmpty())
        const ctxCategories = document.getElementById('chartCategories');
        if (ctxCategories) {
            new Chart(ctxCategories, {
                type: 'doughnut',
                data: {
                    labels: @json($repartitionCategories->pluck('nom')),
                    datasets: [{
                        data: @json($repartitionCategories->pluck('produits_sum_quantite')),
                        backgroundColor: ['#059669', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 8,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 }
                        }
                    }
                }
            });
        }
        @endif
    });
</script>
@endpush