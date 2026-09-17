@php
$isAdmin = auth()->user()->role === 'admin';
$isMobile = $isMobile ?? false;

$sections = [];

if ($isAdmin) {
    $sections = [
        'Vue Globale' => [
            ['label' => 'Dashboard', 'icon' => 'bi bi-grid-1x2-fill', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => 'Statistiques', 'icon' => 'bi bi-bar-chart-fill', 'route' => 'admin.statistiques.index', 'active' => 'admin.statistiques.*'],
        ],
        'Stock & Production' => [
            ['label' => 'Produits', 'icon' => 'bi bi-box-seam-fill', 'route' => 'admin.produits.index', 'active' => 'admin.produits.*'],
            ['label' => 'Catégories', 'icon' => 'bi bi-tags-fill', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
            ['label' => 'Matières premières', 'icon' => 'bi bi-boxes', 'route' => 'admin.matieres-premieres.index', 'active' => 'admin.matieres-premieres.*'],
            ['label' => 'Production', 'icon' => 'bi bi-diagram-3-fill', 'route' => 'admin.production.index', 'active' => 'admin.production.*'],
        ],
        'Flux & Mouvements' => [
            ['label' => 'Entrées de stock', 'icon' => 'bi bi-box-arrow-in-down', 'route' => 'admin.entrees.index', 'active' => 'admin.entrees.*'],
            ['label' => 'Sorties de stock', 'icon' => 'bi bi-box-arrow-up', 'route' => 'admin.sorties.index', 'active' => 'admin.sorties.*'],
            ['label' => 'Fournisseurs', 'icon' => 'bi bi-truck', 'route' => 'admin.fournisseurs.index', 'active' => 'admin.fournisseurs.*'],
        ],
        'Administration' => [
            ['label' => 'Utilisateurs', 'icon' => 'bi bi-people-fill', 'route' => 'admin.utilisateurs.index', 'active' => 'admin.utilisateurs.*'],
            ['label' => 'Activités', 'icon' => 'bi bi-clock-history', 'route' => 'admin.activites.historique', 'active' => 'admin.activites.*'],
            ['label' => 'Sauvegardes', 'icon' => 'bi bi-database-check', 'route' => 'admin.sauvegarde.index', 'active' => 'admin.sauvegarde.*'],
            ['label' => 'Paramètres', 'icon' => 'bi bi-gear-fill', 'route' => 'admin.parametres.index', 'active' => 'admin.parametres.*'],
        ],
    ];
} else {
    $sections = [
        'Vue Globale' => [
            ['label' => 'Dashboard', 'icon' => 'bi bi-grid-1x2-fill', 'route' => 'magasinier.dashboard', 'active' => 'magasinier.dashboard'],
        ],
        'Stock & Articles' => [
            ['label' => 'Produits', 'icon' => 'bi bi-box-seam-fill', 'route' => 'magasinier.produits.index', 'active' => 'magasinier.produits.*'],
            ['label' => 'Fournisseurs', 'icon' => 'bi bi-truck', 'route' => 'magasinier.fournisseurs.index', 'active' => 'magasinier.fournisseurs.*'],
        ],
        'Mouvements de Stock' => [
            ['label' => 'Entrées de stock', 'icon' => 'bi bi-box-arrow-in-down', 'route' => 'magasinier.entrees.index', 'active' => 'magasinier.entrees.*'],
            ['label' => 'Sorties de stock', 'icon' => 'bi bi-box-arrow-up', 'route' => 'magasinier.sorties.index', 'active' => 'magasinier.sorties.*'],
        ],
    ];
}
@endphp

@if(!$isMobile)
<div class="sidebar-header">
    <img src="{{ asset('images/Fa.jpeg') }}" alt="Logo Family" class="sidebar-logo">
    <div class="sidebar-brand-text">
        <h1>Family</h1>
        <span>Gestion de Stock</span>
    </div>
</div>
@endif

<div class="sidebar-body">
    <nav class="sidebar-nav">
        @foreach($sections as $sectionTitle => $items)
            <div class="nav-section-title">{{ $sectionTitle }}</div>
            @foreach($items as $item)
                @php
                    $isActive = request()->routeIs($item['active']);
                @endphp
                <a href="{{ route($item['route']) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endforeach
    </nav>
</div>

<div class="sidebar-footer">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <div class="avatar-circle" style="width: 32px; height: 32px; font-size: 0.8rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="text-truncate">
                <div class="text-white fw-bold small text-truncate" style="line-height: 1.2;">{{ auth()->user()->name }}</div>
                <small class="text-muted" style="font-size: 0.72rem;">{{ $isAdmin ? 'Administrateur' : 'Magasinier' }}</small>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary text-danger border-0 p-1" title="Déconnexion">
                <i class="bi bi-box-arrow-right fs-5"></i>
            </button>
        </form>
    </div>
</div>