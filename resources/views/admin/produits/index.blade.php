@extends('layouts.app')

@section('title', 'Produits - Family')
@section('page-title', 'Gestion des produits')

@section('content')
<div class="container-fluid px-0">

    <!-- En-tête -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Catalogue des Produits</h4>
            <p class="text-muted small mb-0">Consultez, modifiez et gérez l'ensemble des références de stock.</p>
        </div>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.produits.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-plus-lg"></i>
            <span>Nouveau produit</span>
        </a>
        @endif
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($produits->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-box-seam fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <h6>Aucun produit enregistré</h6>
                    <p class="small text-muted mb-3">Commencez par ajouter votre premier produit dans le catalogue.</p>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.produits.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Ajouter un produit
                    </a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table id="produitsTable" class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Description</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-end">Prix unitaire</th>
                                <th class="text-end">Valeur totale</th>
                                @if(auth()->user()->role === 'admin')
                                <th class="text-end pe-3">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($produits as $produit)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $produit->nom }}</div>
                                </td>
                                <td>
                                    @if($produit->categorie)
                                        <span class="badge badge-soft-success">
                                            {{ $produit->categorie->nom }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ Str::limit($produit->description, 45) ?: '—' }}
                                </td>
                                <td class="text-center">
                                    @if($produit->quantite <= 0)
                                        <span class="badge badge-soft-danger">Rupture (0)</span>
                                    @elseif($produit->quantite <= 10)
                                        <span class="badge badge-soft-warning">{{ (int) $produit->quantite }}</span>
                                    @else
                                        <span class="fw-bold text-dark">{{ (int) $produit->quantite }}</span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ $produit->prix == (int) $produit->prix ? number_format($produit->prix, 0, ',', ' ') : number_format($produit->prix, 2, ',', ' ') }} <small class="text-muted">FCFA</small>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    {{ ($produit->quantite * $produit->prix) == (int)($produit->quantite * $produit->prix) ? number_format($produit->quantite * $produit->prix, 0, ',', ' ') : number_format($produit->quantite * $produit->prix, 2, ',', ' ') }} <small class="text-muted">FCFA</small>
                                </td>
                                @if(auth()->user()->role === 'admin')
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.produits.edit', $produit) }}" class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.produits.destroy', $produit) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Confirmez-vous la suppression de ce produit ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#produitsTable').DataTable({
        language: {
            sEmptyTable: "Aucune donnée disponible dans le tableau",
            sInfo: "Affichage de _START_ à _END_ sur _TOTAL_ produits",
            sInfoEmpty: "Affichage de 0 à 0 sur 0 produit",
            sInfoFiltered: "(filtré à partir de _MAX_ produits)",
            sLengthMenu: "Afficher _MENU_ éléments",
            sLoadingRecords: "Chargement...",
            sProcessing: "Traitement...",
            sSearch: "Rechercher :",
            sZeroRecords: "Aucun produit correspondant trouvé",
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
                targets: {{ auth()->user()->role === 'admin' ? 6 : -1 }}
            }
        ]
    });
});
</script>
@endpush