@extends('layouts.app')

@section('title', 'Ajouter une entrée')

@section('page-title', 'Ajouter une entrée de stock')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.entrees.store') }}" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">Produit</label>

                <select name="produit_id" class="form-select @error('produit_id') is-invalid @enderror">

                    <option value="">-- Sélectionner un produit --</option>

                    @foreach($produits as $produit)

                        <option value="{{ $produit->id }}" @selected(old('produit_id') == $produit->id)>

                            {{ $produit->nom }}

                        </option>

                    @endforeach

                </select>

                @error('produit_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Quantité</label>

                <input type="number"
                       name="quantite"
                       min="1"
                       value="{{ old('quantite') }}"
                       class="form-control @error('quantite') is-invalid @enderror">

                @error('quantite')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Date d'entrée</label>

                <input type="date"
                       name="date_entree"
                       value="{{ old('date_entree') }}"
                       class="form-control @error('date_entree') is-invalid @enderror">

                @error('date_entree')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>

            <a href="{{ route('admin.entrees.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

@endsection