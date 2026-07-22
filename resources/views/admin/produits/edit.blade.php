@extends('layouts.app')

@section('title', 'Modifier un produit')

@section('page-title', 'Modifier un produit')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.produits.update', ['produit' => $produit->id]) }}" method="POST">

            @csrf
            @method('PUT')


            <div class="mb-3">

                <label class="form-label">
                    Nom du produit
                </label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom', $produit->nom) }}"
                       class="form-control @error('nom') is-invalid @enderror"
                       required>

                @error('nom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea name="description"
                          rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $produit->description) }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>



            <div class="mb-3">

                <label class="form-label">
                    Catégorie
                </label>

                <select name="categorie_id"
                        class="form-select @error('categorie_id') is-invalid @enderror">


                    <option value="">
                        -- Aucune catégorie --
                    </option>


                    @foreach($categories as $categorie)

                        <option value="{{ $categorie->id }}"
                            @selected(old('categorie_id', $produit->categorie_id) == $categorie->id)>

                            {{ $categorie->nom }}

                        </option>

                    @endforeach


                </select>


                @error('categorie_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>




            <div class="mb-3">

                <label class="form-label">
                    Quantité
                </label>


                <input type="number"
                       name="quantite"
                       min="0"
                       value="{{ old('quantite', $produit->quantite) }}"
                       class="form-control @error('quantite') is-invalid @enderror"
                       required>


                @error('quantite')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror


            </div>




            <div class="mb-4">

                <label class="form-label">
                    Prix
                </label>


                <input type="number"
                       name="prix"
                       step="0.01"
                       min="0"
                       value="{{ old('prix', $produit->prix) }}"
                       class="form-control @error('prix') is-invalid @enderror"
                       required>


                @error('prix')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror


            </div>




            <button type="submit" class="btn btn-primary">

                <i class="bi bi-check-circle"></i>
                Mettre à jour

            </button>


            <a href="{{ route('admin.produits.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Annuler

            </a>


        </form>

    </div>

</div>

@endsection