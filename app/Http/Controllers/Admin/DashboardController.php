<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\In;
use App\Models\ActivityLog;
use App\Models\Out;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{


    public function index()
    {

        $derniereConnexion = ActivityLog::where('user_id', Auth::id())
            ->where('categorie', 'connexion')
            ->where('description', 'like', 'Connexion réussie%')
            ->latest()
            ->skip(1)
            ->first();

        return view('admin.dashboard', [

            'produits' => Product::count(),

            'fournisseurs' => Supplier::count(),

            'entrees' => In::count(),

            'sorties' => Out::count(),

            'stockTotal' => Product::sum('quantite'),

            'derniereConnexion' => $derniereConnexion,

        ]);

    }


}