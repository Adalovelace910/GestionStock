<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\In;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InController extends Controller
{
    public function index(Request $request)
    {
        $query = In::with(['produit.categorie', 'user', 'matierePremiere']);

        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->input('produit_id'));
        }

        $perPage = (int) $request->input('per_page', 20);
        $entrees = $query->latest('date_entree')->latest('id')->paginate($perPage);

        return response()->json([
            'data' => $entrees->items(),
            'total' => $entrees->total(),
            'current_page' => $entrees->currentPage(),
            'last_page' => $entrees->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_entree' => ['required', 'date'],
        ]);

        $entree = null;
        $produit = null;
        $ancienneQuantite = 0.0;

        DB::transaction(function () use ($validated, &$entree, &$produit, &$ancienneQuantite) {
            $produit = Product::lockForUpdate()->findOrFail($validated['produit_id']);
            $ancienneQuantite = (float) $produit->quantite;

            $entree = In::create([
                'produit_id' => $produit->id,
                'quantite' => $validated['quantite'],
                'date_entree' => $validated['date_entree'],
                'user_id' => Auth::id(),
            ]);

            $produit->increment('quantite', $validated['quantite']);
        });

        $produit->refresh();
        $produit->verifierSeuilStock($ancienneQuantite);

        $auteur = Auth::user();
        Notification::create([
            'title' => 'Nouvelle entrée de stock',
            'message' => "{$auteur->name} a enregistré une entrée de {$validated['quantite']} sur \"{$produit->nom}\".",
            'icon' => 'bi-box-arrow-in-down',
        ]);

        ActivityLog::log(
            'operation',
            "A enregistré une entrée de {$validated['quantite']} sur le produit \"{$produit->nom}\""
        );

        return response()->json([
            'message' => 'Entrée enregistrée avec succès.',
            'entree' => $entree->load(['produit.categorie', 'user']),
            'nouveau_stock' => $produit->quantite,
        ], 201);
    }

    public function show(In $entree)
    {
        $entree->load(['produit.categorie', 'user', 'matierePremiere']);

        return response()->json([
            'entree' => $entree,
        ]);
    }

    public function destroy(In $entree)
    {
        if ($entree->production_id) {
            return response()->json([
                'message' => 'Une entrée issue d\'une production ne peut pas être supprimée ici.',
            ], 422);
        }

        $produit = $entree->produit;
        if (!$produit) {
            $entree->delete();
            return response()->json(['message' => 'Entrée supprimée.']);
        }

        try {
            DB::transaction(function () use ($entree, &$produit) {
                $produit = Product::lockForUpdate()->findOrFail($produit->id);

                if ((float) $entree->quantite > (float) $produit->quantite) {
                    throw new \Exception('Stock insuffisant pour annuler cette entrée.');
                }

                $ancienne = (float) $produit->quantite;
                $produit->decrement('quantite', $entree->quantite);
                $entree->delete();

                $produit->refresh();
                $produit->verifierSeuilStock($ancienne);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Entrée supprimée avec succès.']);
    }
}
