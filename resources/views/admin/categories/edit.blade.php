@extends('layouts.app')

@section('title', 'Modifier une catégorie')

@section('page-title', 'Modifier une catégorie')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.categories.update', $categorie) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">Nom de la catégorie</label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom', $categorie->nom) }}"
                       class="form-control @error('nom') is-invalid @enderror">

                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Description</label>

                <textarea name="description"
                          rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $categorie->description) }}</textarea>

                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>

            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

@endsection