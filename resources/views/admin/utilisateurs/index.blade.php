@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('page-title', 'Gestion des utilisateurs')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de tous les utilisateurs du système.
    </p>

    <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary">

        <i class="bi bi-plus-lg me-1"></i>

        Ajouter un utilisateur

    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-body">


        @if($utilisateurs->isEmpty())

            <p class="text-muted mb-0">

                Aucun utilisateur enregistré pour le moment.

            </p>


        @else


            <div class="table-responsive">


                <table id="utilisateursTable" class="table table-bordered table-hover align-middle">


                    <thead class="table-light">


                        <tr>

                            <th>Nom</th>

                            <th>Email</th>

                            <th>Rôle</th>

                            <th class="text-end">Actions</th>

                        </tr>


                    </thead>


                    <tbody>


                        @foreach($utilisateurs as $utilisateur)


                            <tr>


                                <td>{{ $utilisateur->name }}</td>


                                <td>{{ $utilisateur->email }}</td>


                                <td>

                                    <span class="badge bg-primary text-capitalize">

                                        {{ $utilisateur->role }}

                                    </span>


                                </td>


                                <td class="text-end">


                                    <a href="{{ route('admin.utilisateurs.edit', $utilisateur) }}"
                                       class="btn btn-sm btn-outline-primary">


                                        <i class="bi bi-pencil"></i>


                                    </a>



                                    <form action="{{ route('admin.utilisateurs.destroy', $utilisateur) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">


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


    $('#utilisateursTable').DataTable({


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

                targets: 3

            }

        ]


    });


});


</script>


@endpush