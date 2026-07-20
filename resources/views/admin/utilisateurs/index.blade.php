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

                <table class="table align-middle">

                    <thead>

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
                                       class="btn btn-sm btn-outline-primary me-1">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('admin.utilisateurs.destroy', $utilisateur) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?');">

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

            <div class="mt-3">

                {{ $utilisateurs->links() }}

            </div>

        @endif

    </div>

</div>

@endsection