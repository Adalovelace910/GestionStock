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


        <h5 class="mb-0">

            Informations générales

        </h5>


    </div>


    <div class="card-body">



        <h5>

            Compte administrateur

        </h5>




        <div class="row mt-4">



            <div class="col-md-6 mb-3">

                <strong>

                    Compte :

                </strong>


                {{ auth()->user()->name }}


            </div>

            <div class="col-md-6 mb-3">

                <strong>

                    Email :

                </strong>


                {{ auth()->user()->email }}


            </div>


            <div class="col-md-6 mb-3">


                <strong>

                    Rôle :

                </strong>



                {{ auth()->user()->role ?? 'Administrateur' }}



            </div>


            <div class="col-md-6 mb-3">


                <strong>

                    Version :

                </strong>



                1.0.0



            </div>


            <div class="col-md-6 mb-3">


                <strong>

                    Statut :

                </strong>



                <span class="badge bg-success">

                    Actif

                </span>



            </div>


            <div class="col-md-6 mb-3">


                <strong>

                    Dernière connexion :

                </strong>



                {{ auth()->user()->updated_at }}



            </div>


            <div class="col-md-6 mb-3">


                <strong>

                    Adresse IP :

                </strong>



                {{ request()->ip() }}



            </div>

            <div class="col-md-6 mb-3">


                <strong>

                    Stock total :

                </strong>



                {{ $stockTotal }} kg



            </div>




        </div>




        <hr>




        <p>


            Utilisez le menu de gauche pour gérer les activités
            principales et le menu Admin en haut à droite pour
            les paramètres avancés du système.


        </p>
    </div>

</div>

@endsection