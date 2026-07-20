@extends('layouts.app')

@section('title', 'Dashboard Magasinier')

@section('page-title', 'Tableau de bord')

@section('content')

<div class="row">

    <div class="col-md-3 mb-4">

        <div class="card border-primary shadow-sm">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Produits
                </h6>

                <h2>
                    0
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-4">

        <div class="card border-success shadow-sm">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Entrées aujourd'hui
                </h6>

                <h2>
                    0
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-4">

        <div class="card border-warning shadow-sm">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Sorties aujourd'hui
                </h6>

                <h2>
                    0
                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-4">

        <div class="card border-danger shadow-sm">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Produits en rupture
                </h6>

                <h2>
                    0
                </h2>

            </div>

        </div>

    </div>

</div>


<div class="card shadow-sm">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">

            Bienvenue

        </h5>

    </div>

    <div class="card-body">

        <p>

            Bonjour <strong>{{ auth()->user()->name }}</strong>.

        </p>

        <p>

            Vous êtes connecté en tant que <strong>Magasinier</strong>.

        </p>

        <hr>

        <h5>

            Vos principales tâches

        </h5>

        <ul>

            <li>Enregistrer les entrées de stock.</li>

            <li>Enregistrer les sorties de stock.</li>

            <li>Consulter les produits.</li>

            <li>Consulter les fournisseurs.</li>

            <li>Suivre les quantités disponibles.</li>

        </ul>

    </div>

</div>

@endsection