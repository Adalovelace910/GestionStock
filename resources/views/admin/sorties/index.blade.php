@extends('layouts.app')

@section('title', 'Sorties de stock')

@section('page-title', 'Gestion des sorties de stock')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de toutes les sorties de stock enregistrées.
    </p>

    <a href="{{ route('admin.sorties.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter une sortie
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($sorties->isEmpty())

            <p class="text-muted mb-0">
                Aucune sortie enregistrée pour le moment.
            </p>

        @else

            <div class="table-responsive">

                <table id="sortiesTable" class="table table-striped table-hover align-middle">

                    <thead>

                        <tr>

                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Date de sortie</th>
                            <th class="text-end">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($sorties as $sortie)

                            <tr>

                                <td>{{ $sortie->produit->nom ?? '—' }}</td>

                                <td>{{ $sortie->quantite }}</td>

                                <td>{{ $sortie->date_sortie->format('d/m/Y') }}</td>

                                <td class="text-end">

                                    <a href="{{ route('admin.sorties.edit', $sortie) }}"
                                       class="btn btn-sm btn-outline-primary me-1">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('admin.sorties.destroy', $sortie) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette sortie ?');">

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

    $('#sortiesTable').DataTable({

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