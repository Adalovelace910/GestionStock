<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'GestionStock')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DataTables (CSS) -->
    <link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Surcharge couleur : bleu -> vert -->
    <style>

        :root {
            --bs-primary: #198754;
            --bs-primary-rgb: 25, 135, 84;
        }

        .bg-primary {
            background-color: #198754 !important;
        }

        .text-primary {
            color: #198754 !important;
        }

        .border-primary {
            border-color: #198754 !important;
        }

        .btn-primary {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #157347 !important;
            border-color: #146c43 !important;
        }

        .btn-outline-primary {
            color: #198754 !important;
            border-color: #198754 !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active {
            background-color: #198754 !important;
            border-color: #198754 !important;
            color: #fff !important;
        }

        .badge.bg-primary {
            background-color: #198754 !important;
        }

        .nav-link.bg-primary {
            background-color: #198754 !important;
        }

    </style>

</head>


<body class="bg-light " style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" >


<div class="container-fluid px-0" >

<div class="row g-0">


    <!-- SIDEBAR -->

    <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-0">

        <div class="p-4 border-bottom border-secondary">

            <h4 class="text-white fw-bold ">
                GestionStock
            </h4>

        </div>


        <div class="p-3">

            <ul class="nav flex-column">


                <li class="nav-item mb-2">

                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}">

                        <i class="bi bi-speedometer2 me-2" ></i>

                        Tableau de bord

                    </a>

                </li>


                <li class="nav-item mb-2">

                    <a href="{{ route('admin.produits.index') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.produits.*') ? 'bg-primary' : '' }}">

                        <i class="bi bi-box-seam me-2"></i>

                        Produits

                    </a>

                </li>


                <li class="nav-item mb-2">

                    <a href="{{ route('admin.categories.index') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.categories.*') ? 'bg-primary' : '' }}">

                        <i class="bi bi-tags me-2"></i>

                        Catégories

                    </a>

                </li>

                <li class="nav-item mb-2">

                    <a href="{{ route('admin.fournisseurs.index') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.fournisseurs.*') ? 'bg-primary' : '' }}">

                        <i class="bi bi-truck me-2"></i>

                        Fournisseurs

                    </a>

                </li>

                <li class="nav-item mb-2">

                    <a href="{{ route('admin.entrees.index') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.entrees.*') ? 'bg-primary' : '' }}">

                        <i class="bi bi-box-arrow-in-down me-2"></i>

                        Entrées

                    </a>

                </li>

                <li class="nav-item mb-2">

                    <a href="{{ route('admin.sorties.index') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.sorties.*') ? 'bg-primary' : '' }}">

                        <i class="bi bi-box-arrow-up me-2"></i>

                        Sorties

                    </a>

                </li>

                <li class="nav-item mb-2">

                    <a href="{{ route('admin.utilisateurs.index') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.utilisateurs.*') ? 'bg-primary' : '' }}">

                        <i class="bi bi-people me-2"></i>

                        Utilisateurs

                    </a>

                </li>

                <li class="nav-item mb-2">

                    <a href="{{ route('admin.parametres.index') }}"
                       class="nav-link text-white rounded {{ request()->routeIs('admin.parametres.*') ? 'bg-primary' : '' }}">

                        <i class="bi bi-gear me-2"></i>

                        Paramètres

                    </a>

                </li>


            </ul>

        </div>

    </div>

    <!-- CONTENU -->
    <div class="col-md-9 col-lg-10 p-0">

        <!-- TOPBAR -->

        <nav class="navbar navbar-light bg-white shadow-sm px-4">


            <h5 class="mb-0">

                @yield('page-title')

            </h5>



            <div class="d-flex align-items-center">


                <!-- Notifications -->

                <div class="dropdown me-4">

                    <div class="position-relative"
                         role="button"
                         id="notifToggle"
                         data-bs-toggle="dropdown"
                         aria-expanded="false"
                         style="cursor:pointer;">

                        <i class="bi bi-bell fs-4"></i>

                        @php
                            $unreadCount = \App\Models\Notification::whereNull('read_at')->count();
                        @endphp

                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                            </span>
                        @endif

                    </div>

                    <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 320px;" aria-labelledby="notifToggle">

                        @php
                            $recentNotifications = \App\Models\Notification::latest()->take(5)->get();
                        @endphp

                        @forelse($recentNotifications as $notification)

                            <li>
                                <div class="dropdown-item-text small {{ $notification->isRead() ? 'text-muted' : 'fw-bold' }}">
                                    <i class="bi {{ $notification->icon }} me-1"></i>
                                    {{ $notification->title }}
                                    <div class="text-muted fw-normal">{{ $notification->message }}</div>
                                </div>
                            </li>

                        @empty

                            <li><span class="dropdown-item-text text-muted small">Aucune notification.</span></li>

                        @endforelse

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a href="{{ route('admin.notifications.index') }}" class="dropdown-item text-center small">
                                Voir toutes les notifications
                            </a>
                        </li>

                    </ul>

                </div>


                <!-- Menu déroulant Admin -->

                <div class="dropdown">

                    <div class="d-flex align-items-center dropdown-toggle"
                         role="button"
                         id="adminMenuToggle"
                         data-bs-toggle="dropdown"
                         aria-expanded="false"
                         style="cursor:pointer;">


                        <div class="bg-primary text-white rounded-circle 
                                    d-flex align-items-center justify-content-center"
                             style="width:40px;height:40px">


                            {{ strtoupper(substr(auth()->user()->name,0,1)) }}


                        </div>


                        <span class="ms-2 fw-bold">

                            {{ auth()->user()->name }}

                        </span>

                    </div>


                    <ul class="dropdown-menu dropdown-menu-end shadow"
                        aria-labelledby="adminMenuToggle">


                        <li>

                            <a href="{{ route('admin.profil.edit') }}" class="dropdown-item">

                                <i class="bi bi-person-circle me-2"></i>

                                Mon profil

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('admin.profil.password.edit') }}" class="dropdown-item">

                                <i class="bi bi-key me-2"></i>

                                Changer le mot de passe

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('admin.statistiques.index') }}" class="dropdown-item">

                                <i class="bi bi-bar-chart-line me-2"></i>

                                Statistiques détaillées

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('admin.activites.historique') }}" class="dropdown-item">

                                <i class="bi bi-clock-history me-2"></i>

                                Historique des activités

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('admin.activites.journal') }}" class="dropdown-item">

                                <i class="bi bi-journal-text me-2"></i>

                                Journal des opérations

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('admin.sauvegarde.index') }}" class="dropdown-item">

                                <i class="bi bi-database-check me-2"></i>

                                Sauvegarde des données

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('admin.parametres.index') }}" class="dropdown-item">

                                <i class="bi bi-gear me-2"></i>

                                Paramètres système

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('admin.apropos') }}" class="dropdown-item">

                                <i class="bi bi-info-circle me-2"></i>

                                À propos de Family

                            </a>

                        </li>

                        <li>

                            <hr class="dropdown-divider">

                        </li>

                        <li>

                            <form method="POST" action="{{ route('logout') }}">

                                @csrf

                                <button type="submit"
                                        class="dropdown-item text-danger">


                                    <i class="bi bi-box-arrow-right me-2"></i>

                                    Déconnexion


                                </button>

                            </form>

                        </li>

                    </ul>

                </div>


            </div>


        </nav>
        <!-- PAGE -->

        <main class="p-4">


            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif



            @yield('content')


        </main>


    </div>


</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>