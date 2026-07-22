<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\In;
use App\Models\Supplier;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Out;
use App\Models\User;

class StatistiqueController extends Controller
{
    public function index()
    {
        $parametre = Setting::first();

        $seuil = $parametre->seuil_stock_bas ?? 10;

        $stats = [
            'total_produits' => Product::count(),
            'total_categories' => Category::count(),
            'total_fournisseurs' => Supplier::count(),
            'total_utilisateurs' => User::count(),
            'total_entrees' => In::count(),
            'total_sorties' => Out::count(),
            'quantite_entrees' => In::sum('quantite'),
            'quantite_sorties' => Out::sum('quantite'),
            'stock_total' => Product::sum('quantite'),
            'valeur_stock' => Product::selectRaw('SUM(quantite * prix) as total')->value('total') ?? 0,
            'produits_stock_bas' => Product::where('quantite', '<=', $seuil)->count(),
        ];

        $produitsStockBas = Product::where('quantite', '<=', $seuil)
            ->orderBy('quantite')
            ->take(10)
            ->get();

        $topProduits = Product::orderByDesc('quantite')
            ->take(5)
            ->get();

        return view('admin.statistiques.index', compact('stats', 'produitsStockBas', 'topProduits', 'seuil'));
    }
}