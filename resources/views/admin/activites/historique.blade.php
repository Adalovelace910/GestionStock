@extends('layouts.app')

@section('title', 'Historique des activités')

@section('page-title', 'Historique des activités')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($logs->isEmpty())

            <p class="text-muted mb-0">Aucune connexion enregistrée pour le moment.</p>

        @else

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Description</th>
                            <th>Adresse IP</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->user->name ?? 'Utilisateur supprimé' }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>

        @endif

    </div>

</div>

@endsection