<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InController;
use App\Http\Controllers\Api\MatierePremiereController;
use App\Http\Controllers\Api\OutController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::apiResource('categories', CategoryController::class)
        ->parameters(['categories' => 'categorie']);

    Route::apiResource('fournisseurs', SupplierController::class)
        ->parameters(['fournisseurs' => 'fournisseur']);

    Route::apiResource('produits', ProductController::class)
        ->parameters(['produits' => 'produit']);

    Route::apiResource('matieres-premieres', MatierePremiereController::class)
        ->parameters(['matieres-premieres' => 'matierePremiere']);

    Route::apiResource('entrees', InController::class)
        ->only(['index', 'store', 'show', 'destroy'])
        ->parameters(['entrees' => 'entree']);

    Route::apiResource('sorties', OutController::class)
        ->only(['index', 'store', 'show', 'destroy'])
        ->parameters(['sorties' => 'sortie']);
});