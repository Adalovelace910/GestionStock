  <?php 
        $routes = [
            'admin' => [
                 'dashboard' => [
                    'label' => 'Dashboard',
                    'icon' => 'bi bi-speedometer2',
                    'path' => 'admin.dashboard',
                ],
                'categories' => [
                    'label' => 'Catégories',
                    'icon' => 'bi bi-tags',
                    'path' => 'admin.categories.index',
                ],
                'fournisseurs' => [
                    'label' => 'Fournisseurs',
                    'icon' => 'bi bi-truck',
                    'path' => 'admin.fournisseurs.index',
                ],
                'produits' => [
                    'label' => 'Produits',
                    'icon' => 'bi bi-box-seam',
                    'path' => 'admin.produits.index',
                ],
                'production' => [
                    'label' => 'Production',
                    'icon' => 'bi bi-tools',
                    'path' => 'admin.production.index',
                ],
                'matieres-premieres' => [
                    'label' => 'RawMaterials',
                    'icon' => 'bi bi-boxes',
                    'path' => 'admin.matieres-premieres.index',
                ],
                'entrees' => [
                    'label' => 'Entrées',
                    'icon' => 'bi bi-arrow-in-right',
                    'path' => 'admin.entrees.index',
                ],
                'sorties' => [
                    'label' => 'Sorties',
                    'icon' => 'bi bi-arrow-out-left',
                    'path' => 'admin.sorties.index',
                ],
                'utilisateurs' => [
                    'label' => 'Utilisateurs',
                    'icon' => 'bi bi-people',
                    'path' => 'admin.utilisateurs.index',
                ],
                'parametres' => [
                    'label' => 'Paramètres',
                    'icon' => 'bi bi-gear-fill',
                    'path' => 'admin.parametres.index',
                ],
            ],
        'magasinier' => [
                'dashboard' => [
                    'label' => 'Dashboard',
                    'icon' => 'bi bi-speedometer2',
                    'path' => 'magasinier.dashboard',
                ],
                // 'categories' => [
                //     'label' => 'Catégories',
                //     'icon' => 'bi bi-tags',
                //     'path' => '',
                // ],
                'fournisseurs' => [
                    'label' => 'Fournisseurs',
                    'icon' => 'bi bi-truck',
                    'path' => 'magasinier.fournisseurs.index',
                ],
                'produits' => [
                    'label' => 'Produits',
                    'icon' => 'bi bi-box-seam',
                    'path' => 'magasinier.produits.index',
                ],
                // 'production' => [
                //     'label' => 'Production',
                //     'icon' => 'bi bi-tools',
                //     'path' => 'magasinier.production.index',
                // ],
                'entrees' => [
                    'label' => "Entrées",
                    'icon' => "bi bi-arrow-in-right",
                    "path" => "magasinier.entrees.index",
                ],
                "sorties" => [
                    "label" => "Sorties",
                    "icon" => "bi bi-arrow-out-left",
                    "path" => "magasinier.sorties.index",
                ],
            ],
        ]; ?>
 
 <div class="col-md-3 col-lg-2 bg-dark p-0" style="min-height: 100%;">

        <div class="px-4 py-2 border-bottom border-secondary text-center d-flex align-items-center justify-content-center gap-2" style="min-height: 56px;">
            <img src="{{ asset('images/Fa.jpeg') }}" alt="Logo GestionStock" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">

            <h4 class="text-white fw-bold mb-0" style="font-size: 1.1rem;">
                Family
            </h4>
        </div>
        <div class="p-4">
            <ul class="nav flex-column">  
                @if (Auth::user()->role === 'admin')
                    @foreach($routes['admin'] as $route)
                        <li class="nav-item mb-2">
                            <a href="{{ route($route['path']) }}"
                            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs($route['path']) ? 'bg-primary text-white' : 'text-white' }}">
                                <i class="{{ $route['icon'] }}"></i>
                                {{ $route['label'] }}
                            </a>
                        </li>
                    @endforeach  
                    
                @elseif (Auth::user()->role === 'magasinier')
                    @foreach($routes['magasinier'] as $route)
                        <li class="nav-item mb-2">
                            <a href="{{ route($route['path']) }}"
                            class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs($route['path']) ? 'bg-primary text-white' : 'text-white' }}">
                                <i class="{{ $route['icon'] }}"></i>
                                {{ $route['label'] }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>

        </div>

    </div>