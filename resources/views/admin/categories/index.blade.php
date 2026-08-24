@extends('layouts.app')

@section('title', 'Catégories')

@section('page-title', 'Gestion des catégories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

        <h5 class="mb-0">Catégories</h5>   
     

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterCategorieModal">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter 
    </button>

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

<!-- Modale : Ajouter une catégorie -->
<div class="modal fade" id="ajouterCategorieModal" tabindex="-1" aria-labelledby="ajouterCategorieModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('admin.categories.store') }}" method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title" id="ajouterCategorieModalLabel">Ajouter une catégorie</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">Nom de la catégorie</label>

                        <input type="text"
                               name="nom"
                               value="{{ old('nom') }}"
                               class="form-control @error('nom') is-invalid @enderror">

                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-0">

                        <label class="form-label">Description</label>

                        <textarea name="description"
                                  rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>

                </div>

            </form>

        </div>

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

    @if ($errors->any())
        var ajouterCategorieModal = new bootstrap.Modal(document.getElementById('ajouterCategorieModal'));
        ajouterCategorieModal.show();
    @endif

});

</script>

@endpush