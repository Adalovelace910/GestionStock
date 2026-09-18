@extends('layouts.app')
@section('title', 'Ajouter une sortie')
@section('content')
@php
$routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'magasinier';
@endphp
<div class="d-flex justify-content-between align-items-center mb-4">
<h5 class="mb-0">Ajouter une sortie de stock</h5>
<a href="{{ route($routePrefix.'.sorties.index') }}" class="btn btn-outline-secondary"><i class="bi bi-list me-1"></i>Liste</a>
</div>
<div class="row">
<div class="col-md-6">
<div class="card shadow-sm border-0">
<div class="card-body">
<form action="{{ route($routePrefix.'.sorties.store') }}" method="POST">
@csrf
<div class="mb-3">
<label class="form-label">Produit</label>
<select name="produit_id" id="produit" class="form-select @error('produit_id') is-invalid @enderror">
<option value="">-- Sélectionner un produit --</option>
@foreach($produits as $produit)
<option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}" data-stock="{{ $produit->quantite }}" data-categorie="{{ $produit->categorie->nom ?? 'Sans catégorie' }}" @selected(old('produit_id') == $produit->id)>{{ $produit->nom }}</option>
@endforeach
</select>
@error('produit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
<label class="form-label">Quantité sortie</label>
<input type="number" step="1" name="quantite" min="1" value="{{ old('quantite') }}" class="form-control @error('quantite') is-invalid @enderror">
@error('quantite')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-4">
<label class="form-label">Date de sortie</label>
<input type="date" name="date_sortie" value="{{ old('date_sortie') }}" class="form-control @error('date_sortie') is-invalid @enderror">
@error('date_sortie')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<button type="submit" class="btn btn-primary">Enregistrer</button>
<a href="{{ route($routePrefix.'.sorties.index') }}" class="btn btn-outline-secondary">Annuler</a>
</form>
</div>
</div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
const produit = document.getElementById('produit');
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