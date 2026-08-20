<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\In;
use App\Models\ActivityLog;
use App\Models\Out;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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

        $seuil = Setting::first()->seuil_stock_bas ?? 10;

        $moisActuel = now();

        $entreesMois = In::whereMonth('date_entree', $moisActuel->month)
            ->whereYear('date_entree', $moisActuel->year)
            ->sum('quantite');

        $sortiesMois = Out::whereMonth('date_sortie', $moisActuel->month)
            ->whereYear('date_sortie', $moisActuel->year)
            ->sum('quantite');

        $produitsStockBas = Product::where('quantite', '<=', $seuil)
            ->orderBy('quantite')
            ->take(6)
            ->get()
            ->map(function ($produit) use ($seuil) {
                $produit->critique = $produit->quantite <= intdiv($seuil, 2);
                return $produit;
            });

        // --- Mouvements des 3 derniers mois (pour le graphique en courbes) ---

        $moisLabels = collect();
        $moisEntrees = collect();
        $moisSorties = collect();

        $moisFr = [1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        for ($i = 2; $i >= 0; $i--) {

            $mois = now()->subMonths($i);

            $moisLabels->push($moisFr[$mois->month]);

            $moisEntrees->push(
                In::whereMonth('date_entree', $mois->month)
                    ->whereYear('date_entree', $mois->year)
                    ->sum('quantite')
            );

            $moisSorties->push(
                Out::whereMonth('date_sortie', $mois->month)
                    ->whereYear('date_sortie', $mois->year)
                    ->sum('quantite')
            );
        }

        // --- Répartition du stock par catégorie (anneau + légende) ---

        $stockTotal = Product::sum('quantite');

        $repartitionCategories = Category::withSum('produits', 'quantite')
            ->get()
            ->filter(fn ($categorie) => $categorie->produits_sum_quantite > 0)
            ->map(function ($categorie) use ($stockTotal) {
                $categorie->pourcentage = $stockTotal > 0
                    ? round(($categorie->produits_sum_quantite / $stockTotal) * 100)
                    : 0;
                return $categorie;
            })
            ->sortByDesc('produits_sum_quantite')
            ->values();

        // --- Dernières entrées enregistrées ---

        $dernieresEntrees = In::with('produit')
            ->latest('date_entree')
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', [

            'produits' => Product::count(),

            'fournisseurs' => Supplier::count(),

            'stockTotal' => $stockTotal,

            'entreesMois' => $entreesMois,

            'sortiesMois' => $sortiesMois,

            'alertesStockFaible' => Product::where('quantite', '<=', $seuil)->count(),

            'derniereConnexion' => $derniereConnexion,

            'moisLabels' => $moisLabels,

            'moisEntrees' => $moisEntrees,

            'moisSorties' => $moisSorties,

            'repartitionCategories' => $repartitionCategories,

            'dernieresEntrees' => $dernieresEntrees,

            'produitsStockBas' => $produitsStockBas,

            'seuil' => $seuil,

        ]);

    }
}