@extends('layouts.app')

@section('title', 'Paramètres')

@section('page-title', 'Paramètres système')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.parametres.update') }}" method="POST">

            @csrf
            @method('PUT')

            <h5 class="mb-3">Informations de l'entreprise</h5>

            <div class="mb-3">

                <label class="form-label">Nom de l'entreprise</label>

                <input type="text"
                       name="nom_entreprise"
                       value="{{ old('nom_entreprise', $parametre->nom_entreprise) }}"
                       class="form-control @error('nom_entreprise') is-invalid @enderror">

                @error('nom_entreprise')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Adresse</label>

                <input type="text"
                       name="adresse"
                       value="{{ old('adresse', $parametre->adresse) }}"
                       class="form-control @error('adresse') is-invalid @enderror">

                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Téléphone</label>

                <input type="text"
                       name="telephone"
                       value="{{ old('telephone', $parametre->telephone) }}"
                       class="form-control @error('telephone') is-invalid @enderror">

                @error('telephone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <hr>

            <h5 class="mb-3 mt-4">Gestion du stock</h5>

            <div class="mb-3">

                <label class="form-label">Seuil d'alerte stock bas</label>

                <input type="number"
                       name="seuil_stock_bas"
                       min="0"
                       value="{{ old('seuil_stock_bas', $parametre->seuil_stock_bas) }}"
                       class="form-control @error('seuil_stock_bas') is-invalid @enderror">

                <small class="text-muted">
                    Un produit sera considéré en stock faible en dessous de cette quantité.
                </small>

                @error('seuil_stock_bas')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Unité de mesure par défaut</label>

                <input type="text"
                       name="unite_mesure_defaut"
                       value="{{ old('unite_mesure_defaut', $parametre->unite_mesure_defaut) }}"
                       class="form-control @error('unite_mesure_defaut') is-invalid @enderror"
                       placeholder="ex : kg, unité, litre">

                @error('unite_mesure_defaut')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <hr>

            <h5 class="mb-3 mt-4">Affichage</h5>

            <div class="mb-4">

                <label class="form-label">Éléments par page dans les listes</label>

                <input type="number"
                       name="elements_par_page"
                       min="5"
                       max="100"
                       value="{{ old('elements_par_page', $parametre->elements_par_page) }}"
                       class="form-control @error('elements_par_page') is-invalid @enderror">

                @error('elements_par_page')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>

        </form>

    </div>

</div>

@endsection