<ul class="nav nav-pills mb-4">

    <li class="nav-item">
        <a href="{{ route('admin.matieres-premieres.index') }}"
           class="nav-link {{ request()->routeIs('admin.matieres-premieres.*') ? 'active bg-success text-white' : 'text-success' }}">
            <i class="bi bi-box-seam me-1"></i> Matières premières
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.production.index') }}"
           class="nav-link {{ request()->routeIs('admin.production.index') || request()->routeIs('admin.production.edit') ? 'active bg-success text-white' : 'text-success' }}">
            <i class="bi bi-clock-history me-1"></i> Historique des productions
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.production.create') }}"
           class="nav-link {{ request()->routeIs('admin.production.create') || request()->routeIs('admin.production.store') ? 'active bg-success text-white' : 'text-success' }}">
            <i class="bi bi-plus-lg me-1"></i> Faire une production
        </a>
    </li>

</ul>