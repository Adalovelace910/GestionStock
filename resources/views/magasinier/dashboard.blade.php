@extends('layouts.app')

@section('title', 'Dashboard Magasinier')

@section('page-title', 'Tableau de bord')

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
    .dash .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(15, 23, 42, .08); }

    .dash .kpi-icon {
        width: 54px; height: 54px; min-width: 54px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem; color: #fff;
    }
    .dash .kpi-icon.ic-primary { background: #0d6efd; }
    .dash .kpi-icon.ic-success { background: #198754; }
    .dash .kpi-icon.ic-warning { background: #fd7e14; }
    .dash .kpi-icon.ic-danger  { background: #dc3545; }

    .dash .kpi-label { font-size: .85rem; color: #6c757d; margin-bottom: .1rem; }
    .dash .kpi-value { font-size: 1.6rem; font-weight: 700; color: #1a1f2b; line-height: 1.2; }

    .dash .panel {
        background: #fff; border: 1px solid #eef0f2; border-radius: 16px;
        overflow: hidden; box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
    }
    .dash .panel-header { padding: 1rem 1.25rem; font-weight: 600; color: #1a1f2b; border-bottom: 1px solid #f1f2f4; }
    .dash .panel-body { padding: 1.25rem; }

    .dash .stockbas-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: .6rem 0; border-bottom: 1px solid #f8f9fa; color: #333;
    }
    .dash .stockbas-item:last-child { border-bottom: none; }
    .dash .stockbas-badge { background: #fd7e14; color: #fff; font-weight: 700; font-size: .8rem; border-radius: 999px; padding: .2rem .65rem; }
</style>


<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-primary"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="kpi-label">Produits</div>
                <div class="kpi-value">{{ $produits }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-success"><i class="bi bi-box-arrow-in-down"></i></div>
            <div>
                <div class="kpi-label">Entrées aujourd'hui</div>
                <div class="kpi-value">{{ $entreesAujourdhui }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-warning"><i class="bi bi-box-arrow-up"></i></div>
            <div>
                <div class="kpi-label">Sorties aujourd'hui</div>
                <div class="kpi-value">{{ $sortiesAujourdhui }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-icon ic-danger"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="kpi-label">Produits en rupture</div>
                <div class="kpi-value">{{ $produitsRupture }}</div>
            </div>
        </div>
    </div>

</div>


<div class="row g-4">

    <div class="col-md-7">
        <div class="panel">
            <div class="panel-header">Bienvenue</div>
            <div class="panel-body">
                <p>Bonjour <strong>{{ auth()->user()->name }}</strong>.</p>
                <p>Vous êtes connecté en tant que <strong>Magasinier</strong>.</p>
                <hr>
                <h6 class="fw-bold">Vos principales tâches</h6>
                <ul class="mb-0">
                    <li>Enregistrer les entrées de stock.</li>
                    <li>Enregistrer les sorties de stock.</li>
                    <li>Consulter les produits.</li>
                    <li>Consulter les fournisseurs.</li>
                    <li>Suivre les quantités disponibles.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="panel">
            <div class="panel-header">Stock bas (&le; {{ $seuil }})</div>
            <div class="panel-body p-0">
                @forelse($produitsStockBas as $produit)
                    <div class="stockbas-item" style="padding-left:1.25rem; padding-right:1.25rem;">
                        {{ $produit->nom }}
                        <span class="stockbas-badge">{{ $produit->quantite }}</span>
                    </div>
                @empty
                    <div class="stockbas-item text-muted" style="padding-left:1.25rem;">Aucun produit en stock bas.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

</div>

@endsection