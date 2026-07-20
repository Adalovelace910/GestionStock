@extends('layouts.app')

@section('title', 'Modifier un produit')

@section('page-title', 'Modifier un produit')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.produits.update', $produit) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">Nom du produit</label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom', $produit->nom) }}"
                       class="form-control @error('nom') is-invalid @enderror">

                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Description</label>

                <textarea name="description"
                          rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $produit->description) }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Quantité</label>

                <input type="number"
                       name="quantite"
                       min="0"
                       value="{{ old('quantite', $produit->quantite) }}"
                       class="form-control @error('quantite') is-invalid @enderror">

                @error('quantite')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Prix</label>

                <input type="number"
                       step="0.01"
                       name="prix"
                       min="0"
                       value="{{ old('prix', $produit->prix) }}"
                       class="form-control @error('prix') is-invalid @enderror">

                @error('prix')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>

            <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

@endsection