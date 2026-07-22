@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('page-title', 'Ajouter un produit')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.produits.store') }}" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">Nom du produit</label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom') }}"
                       class="form-control @error('nom') is-invalid @enderror">

                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Description</label>

                <textarea name="description"
                          rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Catégorie</label>

                <select name="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror">

                    <option value="">-- Aucune catégorie --</option>

                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" >
                            {{ $categorie->nom }}
                        </option>
                    @endforeach

                </select>

                @error('categorie_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Quantité</label>

                <input type="number"
                       name="quantite"
                       min="0"
                       value="{{ old('quantite', 0) }}"
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
                       value="{{ old('prix') }}"
                       class="form-control @error('prix') is-invalid @enderror">

                @error('prix')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>

            <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

@endsection