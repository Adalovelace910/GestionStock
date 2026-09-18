@extends('layouts.app')

@section('title', 'Modifier une production')

@section('page-title', 'Modifier une production')

@section('content')

<div class="row">

    <div class="col-lg-7">

        @include('admin.production._nav')

        <div class="card shadow-sm border-0">

            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="text-muted mb-4">
                    Seule la date de production peut être modifiée.
                    La matière première et les produits obtenus ne
                    sont pas modifiables ici, afin de ne pas fausser
                    le stock déjà mis à jour.
                </p>

                <div class="mb-3">
                    <label class="form-label">Matière première</label>
                    <input type="text" class="form-control" readonly
                           value="{{ $production->matierePremiere->nom ?? '—' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantité de matière première utilisée</label>
                    <input type="text" class="form-control" readonly
                           value="{{ number_format($production->quantite_matiere_premiere, 0, ',', ' ') }} kg">
                </div>

                <div class="mb-4">
                    <label class="form-label">Produits obtenus</label>
                    <div class="form-control" style="height: auto;">
                        @forelse($production->entrees as $entree)
                            <span class="badge bg-success-subtle text-success-emphasis">
                                {{ $entree->produit->nom ?? '—' }}
                                ({{ number_format($entree->quantite, 0, ',', ' ') }})
                            </span>
                        @empty
                            <span class="text-muted">Aucun</span>
                        @endforelse
                    </div>
                </div>

                <form action="{{ route('admin.production.update', $production) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="date_production" class="form-label">
                            Date de production
                        </label>

                        <input type="date"
                               name="date_production"
                               id="date_production"
                               value="{{ old('date_production', $production->date_production->format('Y-m-d')) }}"
                               class="form-control @error('date_production') is-invalid @enderror"
                               required>

                        @error('date_production')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Enregistrer
                    </button>

                    <a href="{{ route('admin.production.index') }}" class="btn btn-outline-secondary ms-2">
                        Annuler
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection