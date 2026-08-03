<?php

namespace App\Http\Controllers\Magasinier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\In;
use App\Models\Out;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $seuil = Setting::first()->seuil_stock_bas ?? 10;

        $aujourdHui = now()->toDateString();

        return view('magasinier.dashboard', [

            'produits' => Product::count(),

            'entreesAujourdhui' => In::whereDate('date_entree', $aujourdHui)->sum('quantite'),

            'sortiesAujourdhui' => Out::whereDate('date_sortie', $aujourdHui)->sum('quantite'),

            'produitsRupture' => Product::where('quantite', '<=', $seuil)->count(),

            'produitsStockBas' => Product::where('quantite', '<=', $seuil)
                ->orderBy('quantite')
                ->take(5)
                ->get(),

            'seuil' => $seuil,

        ]);
    }
}