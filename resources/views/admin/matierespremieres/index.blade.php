@extends('layouts.app')

@section('title', 'Matières premières')

@section('page-title', 'Gestion des matières premières')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de toutes les matières premières enregistrées.
    </p>

    <a href="{{ route('admin.matieres-premieres.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter une matière première
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($matieresPremieres->isEmpty())

            <p class="text-muted mb-0">
                Aucune matière première enregistrée pour le moment.
            </p>

        @else

            <div class="table-responsive">

                <table id="matieresPremieresTable" class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Valeur du stock</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($matieresPremieres as $matierePremiere)

                            <tr>

                                <td>{{ $matierePremiere->nom }}</td>

                                <td>{{ Str::limit($matierePremiere->description, 40) ?: '—' }}</td>

                                <td>{{ $matierePremiere->quantite }}</td>

                                <td>{{ number_format($matierePremiere->prix, 0, ',', ' ') }} FCFA</td>

                                <td>{{ number_format($matierePremiere->quantite * $matierePremiere->prix, 0, ',', ' ') }} FCFA</td>

                                <td class="text-end">

                                    <a href="{{ route('admin.matieres-premieres.edit', $matierePremiere) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('admin.matieres-premieres.destroy', $matierePremiere) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette matière première ?')">

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

    $('#matieresPremieresTable').DataTable({

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