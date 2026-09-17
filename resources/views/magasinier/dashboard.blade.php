@extends('layouts.app')

@section('title', 'Dashboard Magasinier - Family')
@section('page-title', 'Tableau de bord Magasinier')

@section('content')
<div class="container-fluid px-0">

    <!-- En-tête : Salutation & Raccourcis Magasinier -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Bonjour, {{ auth()->user()->name }} 👋</h3>
            <p class="text-muted small mb-0">Espace opérationnel de gestion des flux d'entrepôt.</p>
        </div>

        <!-- Quick actions -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('magasinier.entrees.create') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-box-arrow-in-down"></i>
                <span>Enregistrer Entrée</span>
            </a>
            <a href="{{ route('magasinier.sorties.create') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                <i class="bi bi-box-arrow-up"></i>
                <span>Enregistrer Sortie</span>
            </a>
            <a href="{{ route('magasinier.produits.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                <i class="bi bi-box-seam"></i>
                <span>Catalogue Produits</span>
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
                    <div class="kpi-title">Articles au catalogue</div>
                    <div class="kpi-number">{{ $produits }}</div>
                    <div class="kpi-subtext">Références actives</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card accent-blue">
                <div class="kpi-icon-box blue">
                    <i class="bi bi-box-arrow-in-down"></i>
                </div>
                <div>
                    <div class="kpi-title">Entrées aujourd'hui</div>
                    <div class="kpi-number">{{ number_format($entreesAujourdhui, 0, ',', ' ') }}</div>
                    <div class="kpi-subtext text-success d-flex align-items-center gap-1">
                        <i class="bi bi-check-circle"></i> Réceptionnées
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card accent-amber">
                <div class="kpi-icon-box amber">
                    <i class="bi bi-box-arrow-up"></i>
                </div>
                <div>
                    <div class="kpi-title">Sorties aujourd'hui</div>
                    <div class="kpi-number">{{ number_format($sortiesAujourdhui, 0, ',', ' ') }}</div>
                    <div class="kpi-subtext text-warning d-flex align-items-center gap-1">
                        <i class="bi bi-arrow-up-right"></i> Expédiées
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card accent-rose">
                <div class="kpi-icon-box rose">
                    <i class="bi bi-exclamation-octagon-fill"></i>
                </div>
                <div>
                    <div class="kpi-title">Stock bas / Ruptures</div>
                    <div class="kpi-number text-danger">{{ $produitsRupture }}</div>
                    <div class="kpi-subtext text-danger">Seuil &le; {{ $seuil }} unités</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tâches & Alertes Magasinier -->
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clipboard2-check-fill text-primary"></i>
                        <span>Missions & Guide Opérationnel</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">Magasinier</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="p-2 rounded-circle bg-success text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-box-arrow-in-down small"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Réception & Entrées</h6>
                                </div>
                                <p class="text-muted small mb-0">
                                    Enregistrez chaque livraison de marchandises en précisant le fournisseur et la quantité réelle vérifiée.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="p-2 rounded-circle bg-warning text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-box-arrow-up small"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Distribution & Sorties</h6>
                                </div>
                                <p class="text-muted small mb-0">
                                    Consignez immédiatement chaque déstockage pour préserver l'exactitude permanente des stocks.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="p-2 rounded-circle bg-info text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-truck small"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Annuaire Fournisseurs</h6>
                                </div>
                                <p class="text-muted small mb-0">
                                    Accédez aux coordonnées directes des fournisseurs pour suivre les commandes et livraisons.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light border border-light-subtle h-100">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="p-2 rounded-circle bg-danger text-white" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-shield-exclamation small"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Surveillance des Seuils</h6>
                                </div>
                                <p class="text-muted small mb-0">
                                    Signalez sans attendre à l'administrateur les références en rupture ou en stock critique.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                        <span>Stock bas (&le; {{ $seuil }} unités)</span>
                    </div>
                    <a href="{{ route('magasinier.produits.index') }}" class="small text-decoration-none fw-semibold text-danger">
                        Consulter <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @forelse($produitsStockBas as $produit)
                        <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-light">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-box text-muted"></i>
                                <div>
                                    <div class="fw-bold text-dark small">{{ $produit->nom }}</div>
                                    <small class="text-muted">{{ $produit->categorie->nom ?? 'Sans catégorie' }}</small>
                                </div>
                            </div>
                            <span class="badge {{ $produit->quantite == 0 ? 'badge-soft-danger' : 'badge-soft-warning' }}">
                                {{ $produit->quantite }} unité(s)
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted small">
                            <i class="bi bi-check-circle text-success fs-2 d-block mb-1"></i>
                            Aucun produit sous le seuil d'alerte.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection