@extends('layouts.app')

@section('title', 'Produits')

@section('page-title', 'Gestion des produits')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de tous les produits enregistrés.
    </p>

    <a href="{{ route('admin.produits.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter un produit
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($produits->isEmpty())

            <p class="text-muted mb-0">
                Aucun produit enregistré pour le moment.
            </p>

        @else

            <div class="table-responsive">

                <table id="produitsTable" class="table table-striped table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Nom</th>
                            <th>Description</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($produits as $produit)

                            <tr>

                                <td>{{ $produit->nom }}</td>

                                <td>{{ Str::limit($produit->description, 40) ?: '—' }}</td>

                                <td>{{ $produit->quantite }}</td>

                                <td>{{ number_format($produit->prix, 2) }}</td>

                                <td class="text-end">

                                    <a href="{{ route('admin.produits.edit', $produit) }}"
                                       class="btn btn-sm btn-outline-primary me-1">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('admin.produits.destroy', $produit) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer ce produit ?');">

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

    $('#produitsTable').DataTable({

        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },

        pageLength: 10,

        lengthMenu: [[10,25,50,100],[10,25,50,100]],

        ordering: true,

        searching: true

    });

});

</script>

@endpush