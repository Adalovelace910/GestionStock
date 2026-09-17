@extends('layouts.app')

@section('title', 'Catégories - Family')
@section('page-title', 'Gestion des catégories')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Catégories de Produits</h4>
            <p class="text-muted small mb-0">Organisez vos articles par famille ou catégorie de stock.</p>
        </div>

        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#ajouterCategorieModal">
            <i class="bi bi-plus-lg"></i>
            <span>Nouvelle catégorie</span>
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($categories->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-tags fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <h6>Aucune catégorie enregistrée</h6>
                    <p class="small text-muted mb-3">Créez votre première catégorie pour organiser votre stock.</p>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ajouterCategorieModal">
                        <i class="bi bi-plus-lg me-1"></i> Ajouter une catégorie
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table id="categoriesTable" class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nom de la catégorie</th>
                                <th>Description</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $categorie)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="p-2 rounded-circle bg-primary-subtle text-primary" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-tag-fill small"></i>
                                            </span>
                                            <span class="fw-bold text-dark">{{ $categorie->nom }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{ Str::limit($categorie->description, 70) ?: '—' }}</td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.categories.edit', $categorie) }}" class="btn btn-outline-primary" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $categorie) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Modal: Ajouter une catégorie -->
<div class="modal fade" id="ajouterCategorieModal" tabindex="-1" aria-labelledby="ajouterCategorieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="ajouterCategorieModalLabel">
                        <i class="bi bi-tag me-2 text-primary"></i>Ajouter une catégorie
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" for="catNomInput">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text"
                               id="catNomInput"
                               name="nom"
                               value="{{ old('nom') }}"
                               placeholder="Ex: Matières plastiques, Produits finis..."
                               class="form-control @error('nom') is-invalid @enderror"
                               required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label" for="catDescInput">Description</label>
                        <textarea id="catDescInput"
                                  name="description"
                                  rows="3"
                                  placeholder="Brève description de la catégorie..."
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
            sEmptyTable: "Aucune donnée disponible dans le tableau",
            sInfo: "Affichage de _START_ à _END_ sur _TOTAL_ catégories",
            sInfoEmpty: "Affichage de 0 à 0 sur 0 catégorie",
            sInfoFiltered: "(filtré à partir de _MAX_ catégories)",
            sLengthMenu: "Afficher _MENU_ éléments",
            sLoadingRecords: "Chargement...",
            sProcessing: "Traitement...",
            sSearch: "Rechercher :",
            sZeroRecords: "Aucun élément correspondant trouvé",
            oPaginate: {
                sFirst: "Premier",
                sLast: "Dernier",
                sNext: "Suivant",
                sPrevious: "Précédent"
            }
        },
        pageLength: 10,
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