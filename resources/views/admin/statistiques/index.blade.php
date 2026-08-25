@extends('layouts.app')
@section('title', 'Statistiques détaillées')
@section('page-title', 'Statistiques détaillées')
@section('content')
<div class="dash">
<style>
.dash{animation:dashFadeIn .4s ease both}
@keyframes dashFadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
.dash .kpi-card{background:#fff;border:1px solid #eef0f2;border-radius:16px;padding:1.25rem 1.5rem;display:flex;align-items:center;gap:1rem;box-shadow:0 1px 3px rgba(15,23,42,.04);transition:transform .18s ease,box-shadow .18s ease}
.dash .kpi-card:hover{transform:translateY(-3px);box-shadow:0 10px 24px rgba(15,23,42,.08)}
.dash .kpi-icon{width:54px;height:54px;min-width:54px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.35rem;color:#fff;background-size:200% 200%}
.dash .kpi-icon.ic-primary{background:linear-gradient(135deg,#0d6efd,#3d8bfd)}
.dash .kpi-icon.ic-success{background:linear-gradient(135deg,#198754,#2fb673)}
.dash .kpi-icon.ic-warning{background:linear-gradient(135deg,#fd7e14,#ffa348)}
.dash .kpi-icon.ic-danger{background:linear-gradient(135deg,#dc3545,#f0616f)}
.dash .kpi-icon.ic-violet{background:linear-gradient(135deg,#6f42c1,#9a6fe0)}
.dash .kpi-label{font-size:.82rem;color:#6c757d;margin-bottom:.15rem;font-weight:500;text-transform:uppercase;letter-spacing:.03em}
.dash .kpi-value{font-size:1.6rem;font-weight:700;color:#1a1f2b;line-height:1.2}
.dash .panel{background:#fff;border:1px solid #eef0f2;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(15,23,42,.04);height:100%;display:flex;flex-direction:column}
.dash .panel-header{padding:1rem 1.25rem;font-weight:600;color:#1a1f2b;border-bottom:1px solid #f1f2f4;display:flex;align-items:center;justify-content:space-between;font-size:.95rem}
.dash .panel-header .badge-soft{font-size:.72rem;font-weight:600;color:#6c757d;background:#f5f6f8;padding:.25rem .6rem;border-radius:20px}
.dash .panel-body{padding:1.25rem;flex:1;display:flex;flex-direction:column}
.dash .chart-box{position:relative;width:100%;flex:1}
.dash .chart-box.h-sm{height:230px}
.dash .chart-box.h-md{height:260px}
.dash .chart-box.h-lg{height:300px}
.dash .mini-stats{font-size:.82rem;color:#8a94a6;margin-top:1rem;padding-top:.85rem;border-top:1px solid #f1f2f4}
.dash .mini-stats strong{color:#1a1f2b}
.dash .empty-state{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#adb5bd;padding:2rem 1rem;text-align:center}
.dash .empty-state i{font-size:2rem;margin-bottom:.5rem;opacity:.6}
.dash .empty-state p{margin:0;font-size:.88rem}
</style>

<div class="row g-4 mb-4">
<div class="col-md-3">
<div class="kpi-card">
<div class="kpi-icon ic-primary"><i class="bi bi-box-seam-fill"></i></div>
<div><div class="kpi-label">Produits</div><div class="kpi-value">{{ $stats['total_produits'] }}</div></div>
</div>
</div>
<div class="col-md-3">
<div class="kpi-card">
<div class="kpi-icon ic-success"><i class="bi bi-stack"></i></div>
<div><div class="kpi-label">Stock total</div><div class="kpi-value">{{ $stats['stock_total'] }}</div></div>
</div>
</div>
<div class="col-md-3">
<div class="kpi-card">
<div class="kpi-icon ic-warning"><i class="bi bi-cash-coin"></i></div>
<div><div class="kpi-label">Valeur du stock</div><div class="kpi-value">{{ number_format($stats['valeur_stock'], 2) }}</div></div>
</div>
</div>
<div class="col-md-3">
<div class="kpi-card">
<div class="kpi-icon ic-danger"><i class="bi bi-exclamation-triangle-fill"></i></div>
<div><div class="kpi-label">Stock bas (&le; {{ $seuil }})</div><div class="kpi-value">{{ $stats['produits_stock_bas'] }}</div></div>
</div>
</div>
<div class="col-md-3">
<div class="kpi-card">
<div class="kpi-icon ic-violet"><i class="bi bi-basket-fill"></i></div>
<div><div class="kpi-label">Valeur matières premières</div><div class="kpi-value">{{ number_format($stats['valeur_matieres_premieres'], 0, ',', ' ') }} FCFA</div></div>
</div>
</div>
</div>

<div class="row g-4 mb-4">
<div class="col-md-6">
<div class="panel">
<div class="panel-header"><span>Résumé des mouvements</span><span class="badge-soft">Entrées / Sorties</span></div>
<div class="panel-body">
<div class="chart-box h-sm"><canvas id="chartMouvements"></canvas></div>
<p class="mini-stats mb-0">Catégories : <strong>{{ $stats['total_categories'] }}</strong> — Fournisseurs : <strong>{{ $stats['total_fournisseurs'] }}</strong> — Utilisateurs : <strong>{{ $stats['total_utilisateurs'] }}</strong></p>
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel">
<div class="panel-header"><span>Top 5 produits</span><span class="badge-soft">Par quantité en stock</span></div>
<div class="panel-body">
@if($topProduits->isEmpty())
<div class="empty-state"><i class="bi bi-inbox"></i><p>Aucun produit enregistré.</p></div>
@else
<div class="chart-box h-sm"><canvas id="chartTopProduits"></canvas></div>
@endif
</div>
</div>
</div>
</div>

<div class="row g-4">
<div class="col-md-6">
<div class="panel">
<div class="panel-header"><span>Produits en stock bas</span><span class="badge-soft">Seuil &le; {{ $seuil }}</span></div>
<div class="panel-body">
@if($produitsStockBas->isEmpty())
<div class="empty-state"><i class="bi bi-check-circle"></i><p>Aucun produit en stock bas actuellement.</p></div>
@else
<div class="chart-box h-sm"><canvas id="chartStockBas"></canvas></div>
@endif
</div>
</div>
</div>
<div class="col-md-6">
<div class="panel">
<div class="panel-header"><span>Répartition du stock</span><span class="badge-soft">Par catégorie</span></div>
<div class="panel-body">
@if($repartitionCategories->isEmpty())
<div class="empty-state"><i class="bi bi-pie-chart"></i><p>Aucune donnée disponible.</p></div>
@else
<div class="chart-box h-sm"><canvas id="chartCategories"></canvas></div>
@endif
</div>
</div>
</div>
</div>

<div class="row g-4 mt-1">
<div class="col-md-12">
<div class="panel">
<div class="panel-header"><span>Matières premières</span><span class="badge-soft">Quantité en stock</span></div>
<div class="panel-body">
@if($matieresPremieres->isEmpty())
<div class="empty-state"><i class="bi bi-box"></i><p>Aucune matière première enregistrée.</p></div>
@else
<div class="chart-box h-lg"><canvas id="chartMatieresPremieres"></canvas></div>
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
(function(){
Chart.defaults.font.family = "'Segoe UI', system-ui, -apple-system, sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#6c757d';
Chart.defaults.plugins.tooltip.backgroundColor = '#1a1f2b';
Chart.defaults.plugins.tooltip.titleColor = '#fff';
Chart.defaults.plugins.tooltip.bodyColor = '#e9ecef';
Chart.defaults.plugins.tooltip.padding = 10;
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.displayColors = false;
Chart.defaults.plugins.tooltip.titleFont = { weight: '600' };

function gradient(ctx, area, colorFrom, colorTo) {
    var g = ctx.createLinearGradient(0, area.top, 0, area.bottom);
    g.addColorStop(0, colorFrom);
    g.addColorStop(1, colorTo);
    return g;
}

new Chart(document.getElementById('chartMouvements'), {
    type: 'bar',
    data: {
        labels: ['Entrées', 'Sorties'],
        datasets: [{
            label: 'Quantité totale',
            data: [{{ $stats['quantite_entrees'] }}, {{ $stats['quantite_sorties'] }}],
            backgroundColor: function(c) {
                if (!c.chart.chartArea) return;
                return c.dataIndex === 0
                    ? gradient(c.chart.ctx, c.chart.chartArea, '#2fb673', '#198754')
                    : gradient(c.chart.ctx, c.chart.chartArea, '#f0616f', '#dc3545');
            },
            borderRadius: 8,
            borderSkipped: false,
            maxBarThickness: 70,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f2f4' } },
            x: { grid: { display: false } }
        }
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
            backgroundColor: function(c) {
                if (!c.chart.chartArea) return;
                return gradient(c.chart.ctx, c.chart.chartArea, '#3d8bfd', '#0d6efd');
            },
            borderRadius: 8,
            borderSkipped: false,
            maxBarThickness: 26,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f2f4' } },
            y: { grid: { display: false } }
        }
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
            backgroundColor: function(c) {
                if (!c.chart.chartArea) return;
                return gradient(c.chart.ctx, c.chart.chartArea, '#f0616f', '#dc3545');
            },
            borderRadius: 8,
            borderSkipped: false,
            maxBarThickness: 26,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f2f4' } },
            y: { grid: { display: false } }
        }
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
            backgroundColor: ['#0d6efd', '#198754', '#fd7e14', '#dc3545', '#6f42c1', '#20c997', '#ffc107', '#0dcaf0'],
            borderColor: '#fff',
            borderWidth: 3,
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'right',
                labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8 }
            },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                        var pct = total ? Math.round((ctx.parsed / total) * 100) : 0;
                        return ' ' + ctx.label + ' : ' + ctx.parsed + ' (' + pct + '%)';
                    }
                }
            }
        }
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
            backgroundColor: function(c) {
                if (!c.chart.chartArea) return;
                return gradient(c.chart.ctx, c.chart.chartArea, '#9a6fe0', '#6f42c1');
            },
            borderRadius: 8,
            borderSkipped: false,
            maxBarThickness: 46,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f2f4' } },
            x: { grid: { display: false } }
        }
    }
});
@endif
})();
</script>
@endpush