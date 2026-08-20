<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Magasinier\DashboardController as MagasinierDashboardController;
use App\Http\Controllers\Admin\{
    ProductController,
    ProductionController,
    MatierePremiereController,
    CategorieController,
    FournisseurController,
    InController,
    OutController,
    RapportController,
    UserController,
    SettingController,
    NotificationController,
    ProfileController,
    StatistiqueController,
    ActivityLogController,
    SauvegardeController,
    PageController
};

/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'create'])
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('login.submit'); //Pour enlever cela , php artisan cache:clear

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = \Illuminate\Support\Facades\Password::sendResetLink(
        $request->only('email')
    );

    return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => \Illuminate\Support\Facades\Hash::make($password)
            ])->save();
        }
    );

    return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.reset.submit');


/*
|--------------------------------------------------------------------------
| Tableau de bord Magasinier
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'magasinier'])
    ->prefix('magasinier')
    ->name('magasinier.')
    ->group(function () {

        Route::get('/dashboard', [MagasinierDashboardController::class, 'index'])
            ->name('dashboard');

        // Consultation seule (pas de create/edit/destroy)
        Route::get('/produits', [ProductController::class, 'index'])
            ->name('produits.index');

        Route::get('/fournisseurs', [FournisseurController::class, 'index'])
            ->name('fournisseurs.index');

        // Entrées et sorties : lecture + ajout + modification (pas de suppression)
        Route::resource('entrees', InController::class)
            ->only(['index', 'create', 'store', 'edit', 'update']);

        Route::resource('sorties', OutController::class)
            ->only(['index', 'create', 'store', 'edit', 'update'])
            ->parameters([
                'sorties' => 'sortie'
            ]);

        // Profil
        Route::get('/profil', [ProfileController::class, 'edit'])
            ->name('profil.edit');

        Route::put('/profil', [ProfileController::class, 'update'])
            ->name('profil.update');

        Route::get('/profil/mot-de-passe', [ProfileController::class, 'editPassword'])
            ->name('profil.password.edit');

        Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])
            ->name('profil.password.update');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');

        Route::put('/notifications/{notification}/lu', [NotificationController::class, 'markAsRead'])
            ->name('notifications.markAsRead');

        Route::put('/notifications/tout-lu', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');

        // À propos
        Route::get('/a-propos', [PageController::class, 'apropos'])
            ->name('apropos');
    });



/*
|--------------------------------------------------------------------------
| Administration
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Dashboard Administrateur
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');



        /*
        |--------------------------------------------------------------------------
        | Gestion
        |--------------------------------------------------------------------------
        */

        Route::resource('produits', ProductController::class);

        // CRUD des matières premières (stock de matière première)
        Route::resource('matieres-premieres', MatierePremiereController::class);

        // Fabrication (transforme une matière première en produits)
        Route::resource('production', ProductionController::class)
    ->except(['show']);

        Route::resource('categories', CategorieController::class);

        Route::resource('fournisseurs', FournisseurController::class);

        Route::resource('entrees', InController::class);

        Route::resource('sorties', OutController::class)
            ->parameters([
                'sorties' => 'sortie'
            ]);
        Route::resource('utilisateurs', UserController::class);



        /*
        |--------------------------------------------------------------------------
        | Rapports
        |--------------------------------------------------------------------------
        */

        Route::get('/rapports/stock-actuel', [RapportController::class, 'stockActuel'])
            ->name('rapports.stock-actuel');

        Route::get('/rapports/mouvements', [RapportController::class, 'mouvements'])
            ->name('rapports.mouvements');



        /*
        |--------------------------------------------------------------------------
        | Paramètres
        |--------------------------------------------------------------------------
        */

        Route::get('/parametres', [SettingController::class, 'index'])
            ->name('parametres.index');

        Route::put('/parametres', [SettingController::class, 'update'])
            ->name('parametres.update');



        /*
        |--------------------------------------------------------------------------
        | Notifications
        |--------------------------------------------------------------------------
        */

        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');

        Route::put('/notifications/{notification}/lu', [NotificationController::class, 'markAsRead'])
            ->name('notifications.markAsRead');

        Route::put('/notifications/tout-lu', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');



        /*
        |--------------------------------------------------------------------------
        | Profil
        |--------------------------------------------------------------------------
        */

        Route::get('/profil', [ProfileController::class, 'edit'])
            ->name('profil.edit');

        Route::put('/profil', [ProfileController::class, 'update'])
            ->name('profil.update');

        Route::get('/profil/mot-de-passe', [ProfileController::class, 'editPassword'])
            ->name('profil.password.edit');

        Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])
            ->name('profil.password.update');



        /*
        |--------------------------------------------------------------------------
        | Statistiques / Activités / Sauvegarde
        |--------------------------------------------------------------------------
        */

        Route::get('/statistiques', [StatistiqueController::class, 'index'])
            ->name('statistiques.index');


        Route::get('/activites/historique', [ActivityLogController::class, 'historique'])
            ->name('activites.historique');


        Route::get('/activites/journal', [ActivityLogController::class, 'journal'])
            ->name('activites.journal');


        Route::get('/sauvegarde', [SauvegardeController::class, 'index'])
            ->name('sauvegarde.index');


        Route::get('/sauvegarde/produits', [SauvegardeController::class, 'exportProduits'])
            ->name('sauvegarde.produits');


        Route::get('/sauvegarde/fournisseurs', [SauvegardeController::class, 'exportFournisseurs'])
            ->name('sauvegarde.fournisseurs');


        Route::get('/sauvegarde/mouvements', [SauvegardeController::class, 'exportMouvements'])
            ->name('sauvegarde.mouvements');



        /*
        |--------------------------------------------------------------------------
        | À propos
        |--------------------------------------------------------------------------
        */

        Route::get('/a-propos', [PageController::class, 'apropos'])
            ->name('apropos');
    });