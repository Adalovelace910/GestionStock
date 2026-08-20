@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('content')

<div class="mb-4">
    <h5 class="mb-0">Ajouter un produit</h5>
</div>

<div class="row">
<div class="col-md-6">

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.produits.store') }}" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">Nom du produit</label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom') }}"
                       class="form-control @error('nom') is-invalid @enderror"
                       required>

                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Catégorie</label>

                <select name="categorie_id"
                        class="form-select @error('categorie_id') is-invalid @enderror"
                        required>

                    <option value="" disabled {{ old('categorie_id') ? '' : 'selected' }}>
                        -- Choisir une catégorie --
                    </option>

                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
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
                       min="{{ $seuil }}"
                       value="{{ old('quantite', $seuil) }}"
                       class="form-control @error('quantite') is-invalid @enderror"
                       required>

                <small class="text-muted">
                    La quantité initiale doit être d'au moins {{ $seuil }} (seuil de stock bas configuré dans les paramètres).
                </small>

                @error('quantite')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Prix</label>

                <input type="number"
                       step="0.01"
                       name="prix"
                       min="0.01"
                       value="{{ old('prix') }}"
                       class="form-control @error('prix') is-invalid @enderror"
                       required>

                @error('prix')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Description</label>

                <textarea name="description"
                          rows="3"
                          class="form-control @error('description') is-invalid @enderror"
                          >{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>

            <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

</div>
</div>

@endsection