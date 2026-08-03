@extends('layouts.app')

@section('title', 'Entrées de stock')

@section('page-title', 'Gestion des entrées de stock')

@section('content')

@php
    $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'magasinier';
@endphp


<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de toutes les entrées de stock enregistrées.
    </p>

    <a href="{{ route($routePrefix.'.entrees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter une entrée
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($entrees->isEmpty())

            <p class="text-muted mb-0">
                Aucune entrée enregistrée pour le moment.
            </p>

        @else

            <div class="table-responsive">

                <table id="entreesTable" class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                            <th>Date d'entrée</th>
                            <th>Ajouté par</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($entrees as $entree)

                            <tr>

                                <td>{{ $entree->produit->nom ?? '—' }}</td>

                                <td>
                                    @if($entree->produit && $entree->produit->categorie)
                                        <span class="badge bg-primary">
                                            {{ $entree->produit->categorie->nom }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>{{ $entree->quantite }}</td>

                                <td>{{ $entree->date_entree->format('d/m/Y') }}</td>

                                <td>{{ $entree->user->name ?? '—' }}</td>

                                <td class="text-end">

                                    <a href="{{ route($routePrefix.'.entrees.edit', $entree) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route($routePrefix.'.entrees.destroy', $entree) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette entrée ?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection

@push('scripts')

<script>

$(document).ready(function () {

    $('#entreesTable').DataTable({

        language: {
                sEmptyTable: "Aucune donnee disponible dans le tableau",
                sInfo: "Affichage de _START_ a _END_ sur _TOTAL_ entrees",
                sInfoEmpty: "Affichage de 0 a 0 sur 0 entree",
                sInfoFiltered: "(filtre a partir de _MAX_ entrees au total)",
                sLengthMenu: "Afficher _MENU_ elements",
                sLoadingRecords: "Chargement...",
                sProcessing: "Traitement...",
                sSearch: "Rechercher :",
                sZeroRecords: "Aucun element correspondant trouve",
                oPaginate: {
                    sFirst: "Premier",
                    sLast: "Dernier",
                    sNext: "Suivant",
                    sPrevious: "Precedent"
                }
            },

        pageLength: 10,

        responsive: true,

        columnDefs: [
            {
                orderable: false,
                targets: 5
            }
        ]

    });

});

</script>

@endpush