@extends('layouts.app')

@section('title', 'Ajouter une matière première')

@section('page-title', 'Ajouter une matière première')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.matieres-premieres.store') }}" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">Nom</label>

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

                <label class="form-label">Description</label>

                <textarea name="description"
                          rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Quantité</label>

                <input type="number"
                       name="quantite"
                       min="1"
                       value="{{ old('quantite', 0) }}"
                       class="form-control @error('quantite') is-invalid @enderror"
                       required>

                @error('quantite')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Prix unitaire</label>

                <input type="number"
                       step="0.01"
                       name="prix"
                       min="1"
                       value="{{ old('prix') }}"
                       class="form-control @error('prix') is-invalid @enderror"
                       required>

                @error('prix')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>

            <a href="{{ route('admin.matieres-premieres.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

@endsection