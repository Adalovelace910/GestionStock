@extends('layouts.app')
@section('title', 'Ajouter une entrée')
@section('page-title', 'Ajouter une entrée')
@section('content')
@php
$routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'magasinier';
@endphp
<div class="d-flex justify-content-between align-items-center mb-3">
<h2 class="fw-bold mb-0">Ajouter une entrée</h2>
<a href="{{ route($routePrefix . '.entrees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-list me-1"></i>Liste</a>
</div>
<div class="row">
<div class="col-lg-7">
<div class="card shadow-sm border-0">
<div class="card-body">
@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<form action="{{ route($routePrefix . '.entrees.store') }}" method="POST">
@csrf
<div class="mb-3">
<label for="produit_id" class="form-label">Produit</label>
<select name="produit_id" id="produit_id" class="form-select" required>
<option value="">-- Sélectionner un produit --</option>
@foreach($produits as $produit)
<option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}" data-stock="{{ $produit->quantite }}" data-categorie="{{ $produit->categorie->nom ?? 'Sans catégorie' }}" @selected(old('produit_id') == $produit->id)>{{ $produit->nom }}</option>
@endforeach
</select>
</div>
<div class="row mb-3">
<div class="col-md-4">
<label class="form-label">Catégorie</label>
<input type="text" id="categorie" class="form-control" readonly>
</div>
<div class="col-md-4">
<label class="form-label">Prix unitaire (FCFA)</label>
<input type="text" id="prix" class="form-control" readonly>
</div>
<div class="col-md-4">
<label class="form-label">Stock actuel</label>
<input type="text" id="stock" class="form-control" readonly>
</div>
</div>
<div class="mb-3">
<label for="quantite" class="form-label">Quantité entrée</label>
<input type="number" name="quantite" id="quantite" min="0.01" step="0.01" value="{{ old('quantite') }}" class="form-control" required>
</div>
<div class="mb-4">
<label for="date_entree" class="form-label">Date d'entrée</label>
<input type="date" name="date_entree" id="date_entree" value="{{ old('date_entree', now()->format('Y-m-d')) }}" class="form-control" required>
</div>
<button type="submit" class="btn btn-primary">Enregistrer l'entrée</button>
<a href="{{ route($routePrefix . '.entrees.index') }}" class="btn btn-outline-secondary ms-2">Annuler</a>
</form>
</div>
</div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
const produit = document.getElementById('produit_id');
const categorie = document.getElementById('categorie');
const prix = document.getElementById('prix');
const stock = document.getElementById('stock');
function afficherInformations(){
let option = produit.options[produit.selectedIndex];
if(option.value){
categorie.value = option.dataset.categorie;
prix.value = Number(option.dataset.prix).toLocaleString('fr-FR') + ' FCFA';
stock.value = option.dataset.stock + ' unité(s)';
} else {
categorie.value = '';
prix.value = '';
stock.value = '';
}
}
produit.addEventListener('change', afficherInformations);
afficherInformations();
});
</script>
@endsection