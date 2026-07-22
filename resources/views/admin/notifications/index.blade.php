@extends('layouts.app')

@section('title', 'Notifications')

@section('page-title', 'Notifications')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <p class="text-muted mb-0">Historique de toutes les notifications système.</p>

    <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-outline-primary">
            <i class="bi bi-check2-all me-1"></i>
            Tout marquer comme lu
        </button>
    </form>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        @if($notifications->isEmpty())

            <p class="text-muted mb-0">Aucune notification pour le moment.</p>

        @else

            <div class="list-group">

                @foreach($notifications as $notification)

                    <div class="list-group-item d-flex justify-content-between align-items-start {{ $notification->isRead() ? '' : 'bg-light' }}">

                        <div class="d-flex">

                            <i class="bi {{ $notification->icon }} fs-4 me-3 text-primary"></i>

                            <div>
                                <strong>{{ $notification->title }}</strong>
                                <div class="text-muted small">{{ $notification->message }}</div>
                                <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>

                        </div>

                        @if(! $notification->isRead())

                            <form action="{{ route('admin.notifications.markAsRead', $notification) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    Marquer comme lu
                                </button>
                            </form>

                        @endif

                    </div>

                @endforeach

            </div>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>

        @endif

    </div>

</div>

@endsection