@extends('layouts.app')

@section('title', 'Fournisseurs')

@section('page-title', 'Gestion des fournisseurs')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">
        Liste de tous les fournisseurs enregistrés.
    </p>

    <a href="{{ route('admin.fournisseurs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Ajouter un fournisseur
    </a>

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
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($fournisseurs as $fournisseur)

                            <tr>

                                <td>{{ $fournisseur->nom }}</td>
                                <td>{{ $fournisseur->telephone ?: '—' }}</td>
                                <td>{{ $fournisseur->email ?: '—' }}</td>
                                <td>{{ $fournisseur->adresse ?: '—' }}</td>

                                <td class="text-end">

                                    <a href="{{ route('admin.fournisseurs.edit', $fournisseur) }}"
                                       class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.fournisseurs.destroy', $fournisseur) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer ce fournisseur ?');">

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

@push('styles')

<link rel="stylesheet"
      href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">

@endpush

@push('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>

<script>

$(document).ready(function () {

    $('#fournisseursTable').DataTable({

        language: {

            url: '//cdn.datatables.net/plug-ins/2.3.2/i18n/fr-FR.json'

        },

        pageLength: 10,

        lengthMenu: [10, 25, 50, 100],

        ordering: true,

        searching: true,

        responsive: true

    });

});

</script>

@endpush