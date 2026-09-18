<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private function seuilStockBas(): int
    {
        return Setting::first()->seuil_stock_bas ?? 10;
    }

    public function index(Request $request)
    {
        $query = Product::with(['categorie', 'matierePremiere']);

        // Recherche par mot clé
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->input('categorie_id'));
        }

        // Filtre stock bas
        if ($request->boolean('stock_bas')) {
            $seuil = $this->seuilStockBas();
            $query->where('quantite', '<=', $seuil);
        }

        $perPage = (int) $request->input('per_page', 20);
        $produits = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $produits->items(),
            'total' => $produits->total(),
            'current_page' => $produits->currentPage(),
            'last_page' => $produits->lastPage(),
            'seuil' => $this->seuilStockBas(),
        ]);
    }

    public function store(Request $request)
    {
        $seuil = $this->seuilStockBas();

        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produits', 'nom')->where(function ($query) use ($request) {
                    return $query->where('categorie_id', $request->categorie_id);
                }),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);

        $produit = Product::create($validated);
        $produit->verifierSeuilStock(PHP_INT_MAX);

        Notification::create([
            'title' => 'Nouveau produit',
            'message' => "Le produit \"{$produit->nom}\" a été ajouté au stock.",
            'icon' => 'bi-box-seam',
        ]);

        ActivityLog::log('operation', "A créé le produit \"{$produit->nom}\"");

        return response()->json([
            'message' => 'Produit créé avec succès.',
            'produit' => $produit->load('categorie'),
        ], 201);
    }

    public function show(Product $produit)
    {
        $produit->load(['categorie', 'matierePremiere', 'entrees.user', 'reglesProduction']);

        return response()->json([
            'produit' => $produit,
            'seuil' => $this->seuilStockBas(),
            'est_stock_bas' => (float) $produit->quantite <= $this->seuilStockBas(),
        ]);
    }

    public function update(Request $request, Product $produit)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produits', 'nom')
                    ->where(function ($query) use ($request) {
                        return $query->where('categorie_id', $request->categorie_id);
                    })
                    ->ignore($produit->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);

        $ancienneQuantite = (float) $produit->quantite;
        $produit->update($validated);
        $produit->verifierSeuilStock($ancienneQuantite);

        ActivityLog::log('operation', "A modifié le produit \"{$produit->nom}\"");

        return response()->json([
            'message' => 'Produit mis à jour avec succès.',
            'produit' => $produit->load('categorie'),
        ]);
    }

    public function destroy(Product $produit)
    {
        $nom = $produit->nom;
        $produit->delete();

        ActivityLog::log('operation', "A supprimé le produit \"{$nom}\"");

        return response()->json([
            'message' => "Produit \"{$nom}\" supprimé avec succès.",
        ]);
    }
}
