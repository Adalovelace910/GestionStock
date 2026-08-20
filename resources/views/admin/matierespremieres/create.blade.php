@extends('layouts.app')

@section('title', 'Ajouter une matière première')

@section('page-title', 'Ajouter une matière première')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold mb-0">Ajouter une matière première</h2>

        <a href="{{ route('admin.matieres-premieres.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Retour
        </a>
    </div>

    @include('admin.production._nav')

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.matieres-premieres.store') }}">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">
                            Nom <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="nom"
                               name="nom"
                               class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom') }}"
                               required>

                        @error('nom')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="quantite" class="form-label">
                            Quantité initiale (kg)
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               id="quantite"
                               name="quantite"
                               class="form-control @error('quantite') is-invalid @enderror"
                               value="{{ old('quantite', 0) }}"
                               min="0"
                               step="0.01"
                               required>

                        @error('quantite')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_ajout" class="form-label">
                            Date d'ajout <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               id="date_ajout"
                               name="date_ajout"
                               class="form-control @error('date_ajout') is-invalid @enderror"
                               value="{{ old('date_ajout', now()->format('Y-m-d')) }}"
                               required>

                        @error('date_ajout')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prix" class="form-label">
                            Prix <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               id="prix"
                               name="prix"
                               class="form-control @error('prix') is-invalid @enderror"
                               value="{{ old('prix', 0) }}"
                               min="0"
                               step="0.01"
                               required>

                        @error('prix')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.matieres-premieres.index') }}"
                       class="btn btn-outline-secondary">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i>
                        Ajouter
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection