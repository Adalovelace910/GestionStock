@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('page-title', 'Tableau de bord Administrateur')


@section('content')


<!-- En-tête du dashboard -->

<div class="mb-4">

    <p class="text-muted">

        Bienvenue dans votre espace d'administration de Family.

    </p>

</div>


<!-- Cartes statistiques -->


<div class="row g-4 mb-4">



    <!-- Produits -->


    <div class="col-md-3">


        <div class="card shadow-sm border-0">


            <div class="card-body text-center">


                <i class="bi bi-box-seam fs-1 text-primary"></i>


                <h6 class="text-muted mt-3">

                    Produits

                </h6>



                <h2 class="fw-bold">

                    {{ $produits }}

                </h2>



            </div>


        </div>


    </div>


    <!-- Fournisseurs -->


    <div class="col-md-3">


        <div class="card shadow-sm border-0">


            <div class="card-body text-center">


                <i class="bi bi-truck fs-1 text-success"></i>



                <h6 class="text-muted mt-3">

                    Fournisseurs

                </h6>



                <h2 class="fw-bold">

                    {{ $fournisseurs }}

                </h2>



            </div>


        </div>


    </div>


    <!-- Entrées -->


    <div class="col-md-3">


        <div class="card shadow-sm border-0">


            <div class="card-body text-center">


                <i class="bi bi-box-arrow-in-down fs-1 text-warning"></i>



                <h6 class="text-muted mt-3">

                    Entrées stock

                </h6>



                <h2 class="fw-bold">

                    {{ $entrees }}

                </h2>



            </div>


        </div>


    </div>

    <!-- Sorties -->


    <div class="col-md-3">


        <div class="card shadow-sm border-0">


            <div class="card-body text-center">


                <i class="bi bi-box-arrow-up fs-1 text-danger"></i>



                <h6 class="text-muted mt-3">

                    Sorties stock

                </h6>



                <h2 class="fw-bold">

                    {{ $sorties }}

                </h2>



            </div>


        </div>


    </div>



</div>


<!-- Bloc informations générales -->


<div class="card shadow-sm border-0">

    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Informations générales</h5>
    </div>

    <div class="card-body">

        <h5>Compte administrateur</h5>

        <div class="row mt-4">

            <div class="col-md-6 mb-4 d-flex">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;min-width:48px;">
                    <i class="bi bi-person fs-5"></i>
                </div>
                <div>
                    <strong>Compte</strong>
                    <div>{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="col-md-6 mb-4 d-flex">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;min-width:48px;">
                    <i class="bi bi-pc-display fs-5"></i>
                </div>
                <div>
                    <strong>Système</strong>
                    <div>GestionStock - Family</div>
                    <div class="text-muted small">Version 1.0.0</div>
                </div>
            </div>

            <div class="col-md-6 mb-4 d-flex">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;min-width:48px;">
                    <i class="bi bi-shield-check fs-5"></i>
                </div>
                <div>
                    <strong>Rôle</strong>
                    <div class="text-capitalize">{{ auth()->user()->role ?? 'Administrateur' }}</div>
                    <div class="text-muted small">
                        {{ auth()->user()->isAdmin() ? 'Accès complet au système' : 'Accès limité' }}
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4 d-flex">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;min-width:48px;">
                    <i class="bi bi-check-circle fs-5"></i>
                </div>
                <div>
                    <strong>Statut du compte</strong>
                    <div><span class="badge bg-success">Actif</span></div>
                    <div class="text-muted small">Compte en cours</div>
                </div>
            </div>

            <div class="col-md-6 mb-4 d-flex">
                <div class="bg-purple bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;min-width:48px;background-color:#f3e8ff;">
                    <i class="bi bi-clock-history fs-5" style="color:#9333ea;"></i>
                </div>
                <div>
                    <strong>Dernière connexion</strong>
                    <div>
                        @if($derniereConnexion)
                            {{ $derniereConnexion->created_at->format('d/m/Y à H:i') }}
                        @else
                            Première connexion
                        @endif
                    </div>
                    <div class="text-muted small">Adresse IP : {{ request()->ip() }}</div>
                </div>
            </div>

            <div class="col-md-6 mb-4 d-flex">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;min-width:48px;">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
                <div>
                    <strong>Stock total</strong>
                    <div>{{ $stockTotal }} article(s)</div>
                    <div class="text-muted small">Dans le système</div>
                </div>
            </div>

        </div>

        <div class="alert alert-primary d-flex align-items-start mt-2 mb-0">
            <i class="bi bi-lightbulb-fill me-2 mt-1"></i>
            <div>
                <strong>Astuce</strong>
                <div>
                    Utilisez le menu de gauche pour gérer les activités principales
                    et le menu Admin en haut à droite pour les paramètres avancés du système.
                </div>
            </div>
        </div>

    </div>

</div>

@endsection