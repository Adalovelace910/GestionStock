<?php

namespace App\Http\Controllers\Admin;

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
    private function routePrefix(): string
    {
        return Auth::user()->role === 'admin'
            ? 'admin'
            : 'magasinier';
    }

    public function index()
    {
        $entrees =
            In::with([
                'produit.categorie',
                'matierePremiere',
                'production.matierePremiere',
                'user',
            ])
            ->latest('date_entree')
            ->paginate(10);

        $produits =
            Product::orderBy('nom')->get();

        return view(
            'admin.entrees.index',
            compact(
                'entrees',
                'produits'
            )
        );
    }

    public function create()
    {
        $produits =
            Product::with('categorie')
                ->orderBy('nom')
                ->get();

        return view(
            'admin.entrees.create',
            compact('produits')
        );
    }

    /*
     * Une entrée classique.
     *
     * Les productions sont créées dans
     * ProductionController et deviennent
     * automatiquement des entrées.
     */
    public function store(Request $request)
    {
        $validated =
            $request->validate([

                'produit_id' =>
                    [
                        'required',
                        'exists:produits,id'
                    ],

                'quantite' =>
                    [
                        'required',
                        'numeric',
                        'min:0.01'
                    ],

                'date_entree' =>
                    [
                        'required',
                        'date'
                    ],
            ]);


        $produit = null;
        $ancienneQuantite = 0.0;

        DB::transaction(function () use (
            $validated,
            &$produit,
            &$ancienneQuantite
        ) {

            $produit =
                Product::lockForUpdate()
                    ->findOrFail(
                        $validated['produit_id']
                    );


            $ancienneQuantite =
                (float)
                $produit->quantite;


            In::create([

                'produit_id' =>
                    $produit->id,

                'quantite' =>
                    $validated['quantite'],

                'date_entree' =>
                    $validated['date_entree'],

                'user_id' =>
                    Auth::id(),

                'production_id' =>
                    null,

                'matiere_premiere_id' =>
                    null,

                'quantite_matiere_premiere' =>
                    null,

                'rendement' =>
                    null,
            ]);


            $produit->increment(
                'quantite',
                $validated['quantite']
            );
        });


        $produit->refresh();

        $produit->verifierSeuilStock(
            $ancienneQuantite
        );


        $auteur =
            Auth::user();


        Notification::create([

            'title' =>
                'Nouvelle entrée de stock',

            'message' =>
                "{$auteur->name} a enregistré "
                . "une entrée de "
                . $validated['quantite']
                . " sur \""
                . $produit->nom
                . "\".",

            'icon' =>
                'bi-box-arrow-in-down',
        ]);


        ActivityLog::log(
            'operation',
            "A enregistré une entrée de "
            . $validated['quantite']
            . " sur le produit \""
            . $produit->nom
            . "\""
        );


        return redirect()
            ->route(
                $this->routePrefix()
                . '.entrees.index'
            )
            ->with(
                'success',
                'Entrée enregistrée avec succès.'
            );
    }

    public function edit(In $entree)
    {
        if ($entree->production_id) {

            return redirect()
                ->route(
                    $this->routePrefix()
                    . '.entrees.index'
                )
                ->with(
                    'error',
                    'Une entrée créée par une production ne peut pas être modifiée ici.'
                );
        }


        $produits =
            Product::with('categorie')
                ->orderBy('nom')
                ->get();


        return view(
            'admin.entrees.edit',
            compact(
                'entree',
                'produits'
            )
        );
    }

    public function update(
        Request $request,
        In $entree
    ) {
        if ($entree->production_id) {

            return redirect()
                ->route(
                    $this->routePrefix()
                    . '.entrees.index'
                )
                ->with(
                    'error',
                    'Une entrée créée par une production ne peut pas être modifiée ici.'
                );
        }


        $validated =
            $request->validate([

                'produit_id' =>
                    [
                        'required',
                        'exists:produits,id'
                    ],

                'quantite' =>
                    [
                        'required',
                        'numeric',
                        'min:0.01'
                    ],

                'date_entree' =>
                    [
                        'required',
                        'date'
                    ],
            ]);


        try {

            DB::transaction(function () use (
                $validated,
                $entree
            ) {

                $ancienProduit =
                    Product::lockForUpdate()
                        ->findOrFail(
                            $entree->produit_id
                        );


                $nouveauProduit =
                    Product::lockForUpdate()
                        ->findOrFail(
                            $validated['produit_id']
                        );


                $ancienStock =
                    (float)
                    $ancienProduit->quantite;


                $stockSansEntree =
                    $ancienStock
                    -
                    (float)
                    $entree->quantite;


                if ($stockSansEntree < 0) {

                    throw new \Exception(
                        'Cette entrée ne peut pas être modifiée car le stock actuel est inférieur à sa quantité.'
                    );
                }


                if (
                    $ancienProduit->id
                    ===
                    $nouveauProduit->id
                ) {

                    $nouveauStock =
                        $stockSansEntree
                        +
                        (float)
                        $validated['quantite'];

                    $nouveauProduit->update([
                        'quantite' =>
                            $nouveauStock
                    ]);

                } else {

                    $nouveauProduitStock =
                        (float)
                        $nouveauProduit->quantite;

                    $ancienProduit->update([
                        'quantite' =>
                            $stockSansEntree
                    ]);

                    $nouveauProduit->update([
                        'quantite' =>
                            $nouveauProduitStock
                            +
                            (float)
                            $validated['quantite']
                    ]);
                }


                $entree->update([

                    'produit_id' =>
                        $nouveauProduit->id,

                    'quantite' =>
                        $validated['quantite'],

                    'date_entree' =>
                        $validated['date_entree'],
                ]);
            });

        } catch (\Exception $e) {

            return back()
                ->withErrors([
                    'quantite' => $e->getMessage()
                ])
                ->withInput();
        }


        return redirect()
            ->route(
                $this->routePrefix()
                . '.entrees.index'
            )
            ->with(
                'success',
                'Entrée modifiée avec succès.'
            );
    }

    public function destroy(In $entree)
    {
        /*
         * Les entrées de production sont
         * protégées car elles sont liées
         * à une production.
         */
        if ($entree->production_id) {

            return back()
                ->with(
                    'error',
                    'Cette entrée provient d’une production. Elle ne peut pas être supprimée directement.'
                );
        }


        $produit = $entree->produit;

        if (!$produit) {

            $entree->delete();

            return redirect()
                ->route(
                    $this->routePrefix()
                    . '.entrees.index'
                )
                ->with(
                    'success',
                    'Entrée supprimée avec succès.'
                );
        }

        $ancienneQuantite = 0.0;

        try {

            DB::transaction(function () use (
                $entree,
                &$produit,
                &$ancienneQuantite
            ) {

                $produit =
                    Product::lockForUpdate()
                        ->findOrFail(
                            $produit->id
                        );

                if (
                    (float)
                    $entree->quantite
                    >
                    (float)
                    $produit->quantite
                ) {

                    throw new \Exception(
                        'Impossible de supprimer : le stock actuel est inférieur à la quantité de cette entrée.'
                    );
                }

                $ancienneQuantite =
                    (float)
                    $produit->quantite;

                $produit->decrement(
                    'quantite',
                    $entree->quantite
                );

                $entree->delete();
            });

        } catch (\Exception $e) {

            return back()->with(
                'error',
                $e->getMessage()
            );
        }


        $produit->refresh();


        $produit->verifierSeuilStock(
            $ancienneQuantite
        );


        return redirect()
            ->route(
                $this->routePrefix()
                . '.entrees.index'
            )
            ->with(
                'success',
                'Entrée supprimée avec succès.'
            );
    }
}