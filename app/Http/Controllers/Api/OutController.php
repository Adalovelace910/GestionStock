<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Out;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutController extends Controller
{
    public function index(Request $request)
    {
        $query = Out::with(['produit.categorie', 'user']);

        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->input('produit_id'));
        }

        $perPage = (int) $request->input('per_page', 20);
        $sorties = $query->latest('date_sortie')->latest('id')->paginate($perPage);

        return response()->json([
            'data' => $sorties->items(),
            'total' => $sorties->total(),
            'current_page' => $sorties->currentPage(),
            'last_page' => $sorties->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'numeric', 'min:0.01'],
            'date_sortie' => ['required', 'date'],
        ]);

        $sortie = null;
        $produit = null;

        try {
            DB::transaction(function () use ($validated, &$sortie, &$produit) {
                $produit = Product::lockForUpdate()->findOrFail($validated['produit_id']);

                if ((float) $validated['quantite'] > (float) $produit->quantite) {
                    throw new \Exception(
                        "Stock insuffisant : il ne reste que {$produit->quantite} de \"{$produit->nom}\" en stock."
                    );
                }

                $ancienneQuantite = (float) $produit->quantite;
                $validated['user_id'] = Auth::id();

                $sortie = Out::create($validated);
                $produit->decrement('quantite', $validated['quantite']);
                $produit->refresh();

                $produit->verifierSeuilStock($ancienneQuantite);

                $auteur = Auth::user();
                Notification::create([
                    'title' => 'Nouvelle sortie de stock',
                    'message' => "{$auteur->name} a enregistré une sortie de {$validated['quantite']} sur \"{$produit->nom}\".",
                    'icon' => 'bi-box-arrow-up',
                ]);

                ActivityLog::log(
                    'operation',
                    "A enregistré une sortie de {$validated['quantite']} sur le produit \"{$produit->nom}\""
                );
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Sortie enregistrée avec succès.',
            'sortie' => $sortie->load(['produit.categorie', 'user']),
            'nouveau_stock' => $produit->quantite,
        ], 201);
    }

    public function show(Out $sortie)
    {
        $sortie->load(['produit.categorie', 'user']);

        return response()->json([
            'sortie' => $sortie,
        ]);
    }

    public function destroy(Out $sortie)
    {
        DB::transaction(function () use ($sortie) {
            $produit = Product::lockForUpdate()->find($sortie->produit_id);
            if ($produit) {
                $produit->increment('quantite', $sortie->quantite);
            }
            $sortie->delete();
        });

        return response()->json([
            'message' => 'Sortie supprimée avec succès et stock réajusté.',
        ]);
    }
}
