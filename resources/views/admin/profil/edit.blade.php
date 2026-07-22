@extends('layouts.app')

@section('title', 'Mon profil')

@section('page-title', 'Mon profil')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.profil.update') }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', $utilisateur->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $utilisateur->email) }}"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Rôle</label>
                <input type="text" value="{{ ucfirst($utilisateur->role) }}" class="form-control" disabled>
                <small class="text-muted">Le rôle ne peut être modifié que par un administrateur, depuis Utilisateurs.</small>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>

        </form>

    </div>

</div>

@endsection