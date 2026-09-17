@php
    $role = auth()->user()->role;
    $routePrefix = $role === 'admin' ? 'admin' : 'magasinier';
    $unreadCount = \App\Models\Notification::whereNull('read_at')->count();
    $recentNotifications = \App\Models\Notification::latest()->take(5)->get();
@endphp

<header class="app-topbar">
    <div class="topbar-left">
        <!-- Mobile Sidebar Hamburger Toggle -->
        <button class="btn btn-outline-secondary d-lg-none d-flex align-items-center justify-content-center p-2 rounded-3 border-0 bg-light" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#mobileSidebar" 
                aria-controls="mobileSidebar"
                title="Ouvrir le menu">
            <i class="bi bi-list fs-4 text-dark"></i>
        </button>

        <div class="d-none d-sm-block">
            <h2 class="topbar-title">@yield('page-title', 'Tableau de bord')</h2>
        </div>

        <span class="page-badge-role {{ $role === 'admin' ? 'role-admin' : 'role-magasinier' }}">
            <i class="bi {{ $role === 'admin' ? 'bi-shield-check' : 'bi-person-badge' }} me-1"></i>
            {{ $role === 'admin' ? 'Administrateur' : 'Magasinier' }}
        </span>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Date indicator -->
        <div class="d-none d-md-flex align-items-center text-muted small gap-1">
            <i class="bi bi-calendar3"></i>
            <span>{{ now()->translatedFormat('d M Y') }}</span>
        </div>

        <!-- Notifications Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light position-relative p-2 rounded-circle border-0 d-flex align-items-center justify-content-center" 
                    id="notifToggle" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false" 
                    style="width: 40px; height: 40px;"
                    title="Notifications">
                <i class="bi bi-bell text-secondary fs-5"></i>
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.65rem;">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <div class="dropdown-menu dropdown-menu-end shadow-lg notif-dropdown p-0" aria-labelledby="notifToggle">
                <div class="notif-header">
                    <span class="fw-bold text-dark small">Notifications</span>
                    @if($unreadCount > 0)
                        <span class="badge bg-primary-subtle text-primary small">{{ $unreadCount }} non lue(s)</span>
                    @endif
                </div>

                <div style="max-height: 320px; overflow-y: auto;">
                    @forelse($recentNotifications as $notification)
                        <div class="notif-item {{ $notification->isRead() ? '' : 'unread' }}">
                            <div class="p-2 rounded-circle {{ $notification->isRead() ? 'bg-light text-muted' : 'bg-primary-subtle text-primary' }}">
                                <i class="bi {{ $notification->icon ?? 'bi-bell' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small {{ $notification->isRead() ? 'text-secondary' : 'fw-bold text-dark' }}">
                                    {{ $notification->title }}
                                </div>
                                <div class="text-muted small" style="font-size: 0.78rem;">
                                    {{ Str::limit($notification->message, 50) }}
                                </div>
                                <div class="text-subtle small mt-1" style="font-size: 0.7rem; color: #94a3b8;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-bell-slash fs-3 d-block mb-1 text-secondary opacity-50"></i>
                            Aucune notification
                        </div>
                    @endforelse
                </div>

                <div class="p-2 text-center bg-light border-top">
                    <a href="{{ route($routePrefix.'.notifications.index') }}" class="text-decoration-none small text-primary fw-semibold">
                        Voir toutes les notifications <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="user-avatar-btn dropdown-toggle" id="userMenuToggle" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-circle">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start">
                    <div class="fw-bold text-dark small" style="line-height: 1.1;">{{ auth()->user()->name }}</div>
                    <small class="text-muted" style="font-size: 0.72rem;">{{ auth()->user()->email }}</small>
                </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2" style="min-width: 230px;" aria-labelledby="userMenuToggle">
                <li class="px-2 py-1 mb-1 border-bottom pb-2">
                    <div class="fw-bold small text-dark">{{ auth()->user()->name }}</div>
                    <div class="text-muted small" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                </li>

                <li>
                    <a href="{{ route($routePrefix.'.profil.edit') }}" class="dropdown-item rounded-2 py-2 small d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-primary"></i>
                        <span>Mon profil</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route($routePrefix.'.profil.password.edit') }}" class="dropdown-item rounded-2 py-2 small d-flex align-items-center gap-2">
                        <i class="bi bi-key text-warning"></i>
                        <span>Changer mot de passe</span>
                    </a>
                </li>

                @if($role === 'admin')
                    <li>
                        <a href="{{ route('admin.statistiques.index') }}" class="dropdown-item rounded-2 py-2 small d-flex align-items-center gap-2">
                            <i class="bi bi-bar-chart-line text-info"></i>
                            <span>Statistiques</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.parametres.index') }}" class="dropdown-item rounded-2 py-2 small d-flex align-items-center gap-2">
                            <i class="bi bi-gear text-secondary"></i>
                            <span>Paramètres système</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.apropos') }}" class="dropdown-item rounded-2 py-2 small d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle text-muted"></i>
                            <span>À propos de Family</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('magasinier.apropos') }}" class="dropdown-item rounded-2 py-2 small d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle text-muted"></i>
                            <span>À propos de Family</span>
                        </a>
                    </li>
                @endif

                <li><hr class="dropdown-divider my-2"></li>

                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-2 py-2 small text-danger d-flex align-items-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>