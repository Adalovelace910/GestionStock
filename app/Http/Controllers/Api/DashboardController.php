<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\In;
use App\Models\Out;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $seuil = Setting::first()->seuil_stock_bas ?? 10;
        $moisActuel = now();

        $stockTotal = (float) Product::sum('quantite');

        $entreesMois = (float) In::whereMonth('date_entree', $moisActuel->month)
            ->whereYear('date_entree', $moisActuel->year)
            ->sum('quantite');

        $sortiesMois = (float) Out::whereMonth('date_sortie', $moisActuel->month)
            ->whereYear('date_sortie', $moisActuel->year)
            ->sum('quantite');

        $alertesStockFaible = Product::where('quantite', '<=', $seuil)->count();

        $produitsStockBas = Product::with('categorie')
            ->where('quantite', '<=', $seuil)
            ->orderBy('quantite')
            ->take(10)
            ->get()
            ->map(function ($produit) use ($seuil) {
                $produit->critique = (float) $produit->quantite <= ($seuil / 2);
                return $produit;
            });

        $dernieresEntrees = In::with(['produit', 'user'])
            ->latest('date_entree')
            ->latest('id')
            ->take(5)
            ->get();

        $dernieresSorties = Out::with(['produit', 'user'])
            ->latest('date_sortie')
            ->latest('id')
            ->take(5)
            ->get();

        return response()->json([
            'kpi' => [
                'total_produits' => Product::count(),
                'total_fournisseurs' => Supplier::count(),
                'stock_total' => $stockTotal,
                'entrees_mois' => $entreesMois,
                'sorties_mois' => $sortiesMois,
                'alertes_stock_faible' => $alertesStockFaible,
            ],
            'seuil' => $seuil,
            'produits_stock_bas' => $produitsStockBas,
            'dernieres_entrees' => $dernieresEntrees,
            'dernieres_sorties' => $dernieresSorties,
        ]);
    }
}
