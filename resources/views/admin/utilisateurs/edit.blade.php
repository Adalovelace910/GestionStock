@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('page-title', 'Modifier un utilisateur')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.utilisateurs.update', $utilisateur) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">Nom complet</label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $utilisateur->name) }}"
                       class="form-control @error('name') is-invalid @enderror">

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Email</label>

                <input type="email"
                       name="email"
                       value="{{ old('email', $utilisateur->email) }}"
                       class="form-control @error('email') is-invalid @enderror">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Mot de passe
                    <small class="text-muted">(laisser vide pour ne pas le changer)</small>
                </label>

                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="mb-3">

                <label class="form-label">Confirmer le mot de passe</label>

                <input type="password"
                       name="password_confirmation"
                       class="form-control">

            </div>

            <div class="mb-4">

                <label class="form-label">Rôle</label>

                <select name="role" class="form-select @error('role') is-invalid @enderror">

                    <option value="">-- Sélectionner un rôle --</option>

                    <option value="admin" @selected(old('role', $utilisateur->role) == 'admin')>Administrateur</option>

                    <option value="magasinier" @selected(old('role', $utilisateur->role) == 'magasinier')>Magasinier</option>

                </select>

                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>

            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>

</div>

@endsection