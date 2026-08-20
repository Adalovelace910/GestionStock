@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold">
            Entrées de stock
        </h2>

    </div>


    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- Message d'erreur --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Produit</th>

                            <th>Quantité</th>

                            <th>Date</th>

                            <th>Production</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($entrees as $entree)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $entree->id }}
                                </td>


                                {{-- Produit --}}
                                <td>
                                    {{ $entree->produit->nom ?? '-' }}
                                </td>


                                {{-- Quantité --}}
                                <td>
                                    {{ $entree->quantite }}
                                </td>


                                {{-- Date --}}
                                <td>
                                    {{ $entree->created_at
                                        ? $entree->created_at->format('d/m/Y')
                                        : '-' }}
                                </td>


                                {{-- Production --}}
                                <td>

                                    @if($entree->production_id)

                                        <span class="badge bg-warning text-dark">
                                            Production
                                        </span>

                                        <br>

                                        <small>
                                            {{ $entree->production->matierePremiere->nom ?? '-' }}

                                            -

                                            {{ $entree->production->quantite_matiere ?? 0 }}
                                            kg
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Entrée manuelle
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center">

                                    Aucune entrée enregistrée.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection