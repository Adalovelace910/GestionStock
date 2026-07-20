<li class="nav-item dropdown">

    <a class="nav-link dropdown-toggle d-flex align-items-center"
       href="#"
       role="button"
       data-bs-toggle="dropdown">

        <i class="bi bi-person-circle fs-4 me-2"></i>

        Admin

    </a>


    <ul class="dropdown-menu dropdown-menu-end shadow">


        <li>
            <h6 class="dropdown-header">
                Menu administrateur
            </h6>
        </li>


        <li>
            <a class="dropdown-item" href="#">
                <i class="bi bi-person me-2"></i>
                Mon profil
            </a>
        </li>


        <li>
            <a class="dropdown-item" href="#">
                <i class="bi bi-key me-2"></i>
                Changer le mot de passe
            </a>
        </li>


        <li>
            <a class="dropdown-item" href="#">
                <i class="bi bi-bar-chart me-2"></i>
                Statistiques détaillées
            </a>
        </li>


        <li>
            <a class="dropdown-item" href="#">
                <i class="bi bi-clock-history me-2"></i>
                Historique des activités
            </a>
        </li>


        <li>
            <a class="dropdown-item" href="#">
                <i class="bi bi-journal-text me-2"></i>
                Journal des opérations
            </a>
        </li>


        <li>
            <a class="dropdown-item" href="#">
                <i class="bi bi-cloud-arrow-down me-2"></i>
                Sauvegarde des données
            </a>
        </li>


        <li>
            <a class="dropdown-item" href="#">
                <i class="bi bi-gear me-2"></i>
                Paramètres système
            </a>
        </li>


        <li>
            <a class="dropdown-item" href="#">
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

                <button class="dropdown-item text-danger">

                    <i class="bi bi-box-arrow-right me-2"></i>

                    Déconnexion

                </button>

            </form>

        </li>


    </ul>

</li>