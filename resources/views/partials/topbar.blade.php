<nav class="navbar navbar-light bg-white shadow-sm px-4 justify-content-end">

            <div class="d-flex align-items-center">


                <!-- Notifications -->

                @php
                    $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'magasinier';
                @endphp

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
                            <a href="{{ route($routePrefix.'.notifications.index') }}" class="dropdown-item text-center small">
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

                    @if(auth()->user()->role === 'admin')

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

                    @else

                        <li>

                            <a href="{{ route('magasinier.profil.edit') }}" class="dropdown-item">

                                <i class="bi bi-person-circle me-2"></i>

                                Mon profil

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('magasinier.profil.password.edit') }}" class="dropdown-item">

                                <i class="bi bi-key me-2"></i>

                                Changer le mot de passe

                            </a>

                        </li>

                        <li>

                            <a href="{{ route('magasinier.apropos') }}" class="dropdown-item">

                                <i class="bi bi-info-circle me-2"></i>

                                À propos de Family

                            </a>

                        </li>

                    @endif

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