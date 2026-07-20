<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Magasinier\DashboardController as MagasinierDashboardController;

use App\Http\Controllers\Admin\{
    ProduitController,
    CategorieController,
    FournisseurController,
    EntreeController,
    SortieController,
    RapportController,
    UtilisateurController,
    ParametreController
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
    ->name('login.submit');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');


/*
|--------------------------------------------------------------------------
| Tableau de bord Magasinier
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/magasinier/dashboard', [MagasinierDashboardController::class, 'index'])
        ->name('magasinier.dashboard');

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
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Gestion
        |--------------------------------------------------------------------------
        */

        Route::resource('produits', ProduitController::class);
        Route::resource('categories', CategorieController::class);
        Route::resource('fournisseurs', FournisseurController::class);
        Route::resource('entrees', EntreeController::class);
        Route::resource('sorties', SortieController::class);
        Route::resource('utilisateurs', UtilisateurController::class);

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

        Route::get('/parametres', [ParametreController::class, 'index'])
            ->name('parametres.index');

        Route::put('/parametres', [ParametreController::class, 'update'])
            ->name('parametres.update');

    });