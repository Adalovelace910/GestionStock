@extends('layouts.app')

@section('title', 'Statistiques détaillées')

@section('page-title', 'Statistiques détaillées')

@section('content')

<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Produits</h6>
                <h3 class="fw-bold">{{ $stats['total_produits'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Stock total</h6>
                <h3 class="fw-bold">{{ $stats['stock_total'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Valeur du stock</h6>
                <h3 class="fw-bold">{{ number_format($stats['valeur_stock'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <h6 class="text-muted">Produits en stock bas (≤ {{ $seuil }})</h6>
                <h3 class="fw-bold text-danger">{{ $stats['produits_stock_bas'] }}</h3>
            </div>
        </div>
    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">Résumé des mouvements</div>
            <div class="card-body">
                <p>Total entrées : <strong>{{ $stats['total_entrees'] }}</strong> ({{ $stats['quantite_entrees'] }} unités)</p>
                <p>Total sorties : <strong>{{ $stats['total_sorties'] }}</strong> ({{ $stats['quantite_sorties'] }} unités)</p>
                <p class="mb-0">Catégories : <strong>{{ $stats['total_categories'] }}</strong> — Fournisseurs : <strong>{{ $stats['total_fournisseurs'] }}</strong> — Utilisateurs : <strong>{{ $stats['total_utilisateurs'] }}</strong></p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">Top 5 produits (par quantité)</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        @forelse($topProduits as $produit)
                            <tr>
                                <td>{{ $produit->nom }}</td>
                                <td class="text-end">{{ $produit->quantite }}</td>
                            </tr>
                        @empty
                            <tr><td class="text-muted p-3">Aucun produit.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-header bg-danger text-white">Produits en stock bas</div>

    <div class="card-body p-0">

        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité restante</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produitsStockBas as $produit)
                    <tr>
                        <td>{{ $produit->nom }}</td>
                        <td>{{ $produit->quantite }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-muted p-3">Aucun produit en stock bas actuellement.</td></tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection