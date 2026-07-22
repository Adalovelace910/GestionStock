@extends('layouts.app')

@section('title', 'Changer le mot de passe')

@section('page-title', 'Changer le mot de passe')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form action="{{ route('admin.profil.password.update') }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password"
                       class="form-control @error('current_password') is-invalid @enderror">
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>

        </form>

    </div>

</div>

@endsection
