@extends('layouts.app')

@section('title', 'Sauvegarde des données')

@section('page-title', 'Sauvegarde des données')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <p class="text-muted">
            Téléchargez vos données au format CSV, exploitable dans Excel ou Google Sheets.
        </p>

        <div class="d-flex gap-2 flex-wrap">

            <a href="{{ route('admin.sauvegarde.produits') }}" class="btn btn-primary">
                <i class="bi bi-download me-1"></i>
                Exporter les produits
            </a>

            <a href="{{ route('admin.sauvegarde.fournisseurs') }}" class="btn btn-primary">
                <i class="bi bi-download me-1"></i>
                Exporter les fournisseurs
            </a>

            <a href="{{ route('admin.sauvegarde.mouvements') }}" class="btn btn-primary">
                <i class="bi bi-download me-1"></i>
                Exporter les mouvements de stock (entrées + sorties)
            </a>

        </div>

    </div>

</div>

@endsection