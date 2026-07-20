<div class="p-4 border-bottom border-secondary">

    <h4 class="text-white fw-bold">

        <i class="bi bi-box-seam"></i>

        Family

    </h4>

</div>


<div class="p-3">

    <ul class="nav flex-column">


        <li class="nav-item mb-2">

            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link text-white rounded {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}">

                <i class="bi bi-speedometer2 me-2"></i>

                Dashboard

            </a>

        </li>



        <li class="nav-item mb-2">

            <a href="{{ route('admin.produits.index') }}" 
               class="nav-link text-white rounded {{ request()->routeIs('admin.produits.*') ? 'bg-primary' : '' }}">

                <i class="bi bi-box me-2"></i>

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

                <i class="bi bi-arrow-down-square me-2"></i>

                Entrées

            </a>

        </li>



        <li class="nav-item mb-2">

            <a href="{{ route('admin.sorties.index') }}" 
               class="nav-link text-white rounded {{ request()->routeIs('admin.sorties.*') ? 'bg-primary' : '' }}">

                <i class="bi bi-arrow-up-square me-2"></i>

                Sorties

            </a>

        </li>



        <li class="nav-item mb-2">

            <a href="#" 
               class="nav-link text-white">

                <i class="bi bi-clipboard-data me-2"></i>

                Consulter le stock

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



        <li class="nav-item mb-2">

            <a href="#" 
               class="nav-link text-white">

                <i class="bi bi-file-earmark-text me-2"></i>

                Rapports

            </a>

        </li>


    </ul>

</div>