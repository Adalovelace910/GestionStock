@extends('layouts.app')

@section('title', 'À propos')

@section('page-title', 'À propos de Family')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <h5>{{ $parametre->nom_entreprise ?? 'Family' }}</h5>

        <p class="text-muted">GestionStock — application de gestion de stock.</p>

        <hr>

        <p class="mb-1"><strong>Version :</strong> 1.0.0</p>

        <p class="mb-1"><strong>Adresse : AGBANLEPEDO</strong> {{ $parametre->adresse ?? '—' }}</p>

        <p class="mb-1"><strong>Téléphone :</strong> {{ $parametre->telephone ?? '—' }}</p>

        <p class="mb-0">
            <strong>Site web :</strong>
            <a href="https://exemple-a-remplacer.com" target="_blank" rel="noopener noreferrer">
                https://exemple-a-remplacer.com
            </a>
        </p>

    </div>

</div>

@endsection