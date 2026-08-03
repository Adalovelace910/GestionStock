@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('page-title', 'Tableau de bord Administrateur')


@section('content')

<div class="dash">

<style>
    .dash { animation: dashFadeIn .4s ease both; }
    @keyframes dashFadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    .dash .welcome { color: #6c757d; font-size: 1rem; }

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
    .dash .kpi-sub { font-size: .75rem; color: #adb5bd; }

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

    .dash table.dash-table { width: 100%; font-size: .9rem; }
    .dash table.dash-table th {
        text-transform: uppercase;
        font-size: .7rem;
        letter-spacing: .04em;
        color: #adb5bd;
        font-weight: 600;
        padding-bottom: .6rem;
        border-bottom: 1px solid #f1f2f4;
    }
    .dash table.dash-table td {
        padding: .55rem 0;
        border-bottom: 1px solid #f8f9fa;
        color: #343a40;
    }
    .dash table.dash-table tr:last-child td { border-bottom: none; }

    .dash .badge-critique { background: #dc3545; color: #fff; font-weight: 600; font-size: .72rem; border-radius: 999px; padding: .2rem .6rem; }
    .dash .badge-faible   { background: #ffc107; color: #664d03; font-weight: 600; font-size: .72rem; border-radius: 999px; padding: .2rem .6rem; }

    .dash .legend-item { display: flex; align-items: center; gap: .6rem; margin-bottom: .85rem; }
    .dash .legend-dot { width: 11px; height: 11px; border-radius: 50%; flex-shrink: 0; }
    .dash .legend-text strong { display: block; font-size: .88rem; color: #1a1f2b; }
    .dash .legend-text span { font-size: .78rem; color: #8a94a6; }
</style>


<!-- En-tête du dashboard -->

<div class="mb-4">

    @if($derniereConnexion)
        <p class="text-muted small mb-0">
            <i class="bi bi-clock-history"></i>
            Dernière connexion : {{ $derniereConnexion->created_at->format('d/m/Y à H:i') }}
            @if($derniereConnexion->ip_address)
                depuis {{ $derniereConnexion->ip_address }}
            @endif
        </p>
    @endif
</div>


<!-- Cartes KPI -->

<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-primary"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="kpi-label">Stock total</div>
                <div class="kpi-value">{{ number_format($stockTotal, 0, ',', ' ') }}</div>
                <div class="kpi-sub">Quantité totale en stock</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-success"><i class="bi bi-arrow-down"></i></div>
            <div>
                <div class="kpi-label">Entrées ce mois</div>
                <div class="kpi-value">{{ number_format($entreesMois, 0, ',', ' ') }}</div>
                <div class="kpi-sub">Quantité entrée</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-warning"><i class="bi bi-arrow-up"></i></div>
            <div>
                <div class="kpi-label">Sorties ce mois</div>
                <div class="kpi-value">{{ number_format($sortiesMois, 0, ',', ' ') }}</div>
                <div class="kpi-sub">Quantité sortie</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-danger"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="kpi-label">Alertes stock faible</div>
                <div class="kpi-value">{{ $alertesStockFaible }}</div>
                <div class="kpi-sub">Produits en alerte</div>
            </div>
        </div>
    </div>

</div>


<!-- Graphique courbes + Répartition catégories -->

<div class="row g-4 mb-4">

    <div class="col-md-7">
        <div class="panel">
            <div class="panel-header">Mouvements des 3 derniers mois</div>
            <div class="panel-body">
                <canvas id="chartMouvements" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="panel">
            <div class="panel-header">Répartition du stock par catégorie</div>
            <div class="panel-body">
                @if($repartitionCategories->isEmpty())
                    <p class="text-muted mb-0">Aucune donnée disponible.</p>
                @else
                    <div class="row align-items-center">
                        <div class="col-6">
                            <canvas id="chartCategories"></canvas>
                        </div>
                        <div class="col-6">
                            @foreach($repartitionCategories as $i => $categorie)
                                <div class="legend-item">
                                    <span class="legend-dot" style="background: {{ ['#0d6efd','#198754','#fd7e14','#dc3545','#6f42c1','#20c997'][$i % 6] }}"></span>
                                    <div class="legend-text">
                                        <strong>{{ $categorie->nom }}</strong>
                                        <span>{{ number_format($categorie->produits_sum_quantite, 0, ',', ' ') }} ({{ $categorie->pourcentage }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>


<!-- Dernières entrées + Stock faible -->

<div class="row g-4">

    <div class="col-md-7">
        <div class="panel">
            <div class="panel-header">Dernières entrées</div>
            <div class="panel-body">
                @if($dernieresEntrees->isEmpty())
                    <p class="text-muted mb-0">Aucune entrée enregistrée.</p>
                @else
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Produit</th>
                                <th>Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dernieresEntrees as $entree)
                                <tr>
                                    <td>{{ $entree->date_entree->format('d/m/Y') }}</td>
                                    <td>{{ $entree->produit->nom ?? '—' }}</td>
                                    <td>{{ $entree->quantite }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="panel">
            <div class="panel-header">Produits en stock faible</div>
            <div class="panel-body">
                @if($produitsStockBas->isEmpty())
                    <p class="text-muted mb-0">Aucun produit en stock faible.</p>
                @else
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produitsStockBas as $produit)
                                <tr>
                                    <td>{{ $produit->nom }}</td>
                                    <td>{{ $produit->quantite }}</td>
                                    <td>
                                        @if($produit->critique)
                                            <span class="badge-critique">Critique</span>
                                        @else
                                            <span class="badge-faible">Faible</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

    new Chart(document.getElementById('chartMouvements'), {
        type: 'line',
        data: {
            labels: @json($moisLabels),
            datasets: [
                {
                    label: 'Entrées',
                    data: @json($moisEntrees),
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,.1)',
                    fill: true,
                    tension: .35,
                },
                {
                    label: 'Sorties',
                    data: @json($moisSorties),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220,53,69,.08)',
                    fill: true,
                    tension: .35,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    @if($repartitionCategories->isNotEmpty())
    new Chart(document.getElementById('chartCategories'), {
        type: 'doughnut',
        data: {
            labels: @json($repartitionCategories->pluck('nom')),
            datasets: [{
                data: @json($repartitionCategories->pluck('produits_sum_quantite')),
                backgroundColor: ['#0d6efd','#198754','#fd7e14','#dc3545','#6f42c1','#20c997'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            plugins: { legend: { display: false } }
        }
    });
    @endif

</script>

@endpush