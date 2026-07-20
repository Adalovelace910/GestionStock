@extends('layouts.app')

@section('title', 'Ajouter un fournisseur')

@section('page-title', 'Ajouter un fournisseur')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.fournisseurs.store') }}" method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">Nom du fournisseur</label>

                <input type="text"
                       name="nom"
                       value="{{ old('nom') }}"
                       class="form-control @error('nom') is-invalid @enderror">

                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Téléphone</label>

                <input type="text"
                       name="telephone"
                       value="{{ old('telephone') }}"
                       class="form-control @error('telephone') is-invalid @enderror">

                @error('telephone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Email</label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-4">

                <label class="form-label">Adresse</label>

                <input type="text"
                       name="adresse"
                       value="{{ old('adresse') }}"
                       class="form-control @error('adresse') is-invalid @enderror">

                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>

            <a href="{{ route('admin.fournisseurs.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

@endsection