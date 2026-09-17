@extends('layouts.app')

@section('title', 'À propos')

@section('page-title', 'À propos de Family')

@section('content')

@php
    $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'magasinier';
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">

    <h2 class="fw-bold mb-0">À propos de Family</h2>

    <a href="{{ route($routePrefix.'.dashboard') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Retour
    </a>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <h5>{{ $parametre->nom_entreprise ?? 'Family' }}</h5>

        <p class="text-muted">GestionStock — application de gestion de stock.</p>

        <hr>

        <p class="mb-1"><strong>Version :</strong> 1.0.0</p>

        <p class="mb-1"><strong>Adresse : AGBANLEPEDO</strong> {{ $parametre->adresse ?? '—' }}</p>

        <p class="mb-1"><strong>Téléphone: 90 56 29 50</strong> {{ $parametre->telephone ?? '—' }}</p>

        <p class="mb-0">
            <strong>Site web :</strong>
            <a href="https://exemple-a-remplacer.com" target="_blank" rel="noopener noreferrer">
                https://www.goafricaonline.com
            </a>
        </p>

    </div>

</div>

@endsection