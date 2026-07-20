@extends('layouts.app')

@section('title', 'Entrées de stock')

@section('page-title', 'Gestion des entrées de stock')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de toutes les entrées de stock enregistrées.
    </p>

    <a href="{{ route('admin.entrees.create') }}" class="btn btn-primary">
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

                <table id="entreesTable" class="table table-striped table-hover align-middle">

                    <thead>

                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Date d'entrée</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($entrees as $entree)

                            <tr>

                                <td>{{ $entree->produit->nom ?? '—' }}</td>

                                <td>{{ $entree->quantite }}</td>

                                <td>{{ $entree->date_entree->format('d/m/Y') }}</td>

                                <td class="text-end">

                                    <a href="{{ route('admin.entrees.edit', $entree) }}"
                                       class="btn btn-sm btn-outline-primary me-1">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('admin.entrees.destroy', $entree) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette entrée ?');">

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

    $('#entreesTable').DataTable({

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