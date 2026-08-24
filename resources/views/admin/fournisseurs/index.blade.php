@extends('layouts.app')

@section('title', 'Fournisseurs')

@section('page-title', 'Gestion des fournisseurs')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h5 class="mb-0">Fournisseurs</h5>

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.fournisseurs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter 
    </a>
    @endif

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($fournisseurs->isEmpty())

            <p class="text-muted mb-0">
                Aucun fournisseur enregistré pour le moment.
            </p>

        @else

            <div class="table-responsive">

                <table id="fournisseursTable" class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Adresse</th>
                            @if(auth()->user()->role === 'admin')
                            <th class="text-end">Actions</th>
                            @endif
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($fournisseurs as $fournisseur)

                            <tr>

                                <td>{{ $fournisseur->nom }}</td>
                                <td>{{ $fournisseur->telephone ?: '—' }}</td>
                                <td>{{ $fournisseur->email ?: '—' }}</td>
                                <td>{{ $fournisseur->adresse ?: '—' }}</td>

                                @if(auth()->user()->role === 'admin')
                                <td class="text-end">

                                    <a href="{{ route('admin.fournisseurs.edit', $fournisseur) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('admin.fournisseurs.destroy', $fournisseur) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer ce fournisseur ?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

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

@endsection

@push('scripts')

<script>

$(document).ready(function () {

    $('#fournisseursTable').DataTable({

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
                targets: {{ auth()->user()->role === 'admin' ? 4 : -1 }}
            }
        ]

    });

});

</script>

@endpush