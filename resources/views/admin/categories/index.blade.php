@extends('layouts.app')

@section('title', 'Catégories')

@section('page-title', 'Gestion des catégories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de toutes les catégories enregistrées.
    </p>

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter une catégorie
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($categories->isEmpty())

            <p class="text-muted mb-0">
                Aucune catégorie enregistrée pour le moment.
            </p>

        @else

            <div class="table-responsive">

                <table id="categoriesTable" class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($categories as $categorie)

                            <tr>

                                <td>{{ $categorie->nom }}</td>

                                <td>{{ Str::limit($categorie->description, 60) ?: '—' }}</td>

                                <td class="text-end">

                                    <a href="{{ route('admin.categories.edit', $categorie) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $categorie) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette catégorie ?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

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

    $('#categoriesTable').DataTable({

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
                targets: 2
            }
        ]

    });

});

</script>

@endpush