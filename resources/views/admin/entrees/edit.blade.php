@extends('layouts.app')
@section('title', 'Modifier une entrée')
@section('page-title', 'Modifier une entrée')
@section('content')
@php
$routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'magasinier';
@endphp
<div class="d-flex justify-content-between align-items-center mb-3">
<h2 class="fw-bold mb-0">Modifier une entrée</h2>
<a href="{{ route($routePrefix . '.entrees.index') }}" class="btn btn-outline-secondary"><i class="bi bi-list me-1"></i>Liste</a>
</div>
<div class="row">
<div class="col-lg-7">
<div class="card shadow-sm border-0">
<div class="card-body">
@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<form action="{{ route($routePrefix . '.entrees.update', $entree) }}" method="POST">
@csrf
@method('PUT')
<div class="mb-3">
<label for="produit_id" class="form-label">Produit</label>
<select name="produit_id" id="produit_id" class="form-select" required>
<option value="">-- Sélectionner un produit --</option>
@foreach($produits as $produit)
<option value="{{ $produit->id }}" @selected(old('produit_id', $entree->produit_id) == $produit->id)>{{ $produit->nom }}</option>
@endforeach
</select>
</div>
<div class="mb-3">
<label for="quantite" class="form-label">Quantité entrée</label>
<input type="number" name="quantite" id="quantite" min="0.01" step="0.01" value="{{ old('quantite', $entree->quantite) }}" class="form-control" required>
</div>
<div class="mb-4">
<label for="date_entree" class="form-label">Date d'entrée</label>
<input type="date" name="date_entree" id="date_entree" value="{{ old('date_entree', optional($entree->date_entree)->format('Y-m-d')) }}" class="form-control" required>
</div>
<button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
<a href="{{ route($routePrefix . '.entrees.index') }}" class="btn btn-outline-secondary ms-2">Annuler</a>
</form>
</div>
</div>
</div>
</div>
@endsection