<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Models\Produit;
use App\Models\Fournisseur;
use App\Models\Entree;
use App\Models\Sortie;


class DashboardController extends Controller
{


    public function index()
    {

        return view('admin.dashboard', [

            'produits' => Produit::count(),

            'fournisseurs' => Fournisseur::count(),

            'entrees' => Entree::count(),

            'sorties' => Sortie::count(),

            'stockTotal' => Produit::sum('quantite'),

        ]);

    }


}