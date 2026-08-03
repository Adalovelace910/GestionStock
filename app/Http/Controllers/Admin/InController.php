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
        return Auth::user()->role === 'admin' ? 'admin' : 'magasinier';
    }


    public function index()
    {
        $entrees = In::with(['produit.categorie', 'user'])->latest('date_entree')
            ->paginate(10);

        return view('admin.entrees.index', compact('entrees'));
    }


    public function create()
    {
        $produits = Product::with('categorie')
            ->orderBy('nom')
            ->get();

        return view('admin.entrees.create', compact('produits'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_entree' => ['required', 'date'],
        ]);


        DB::transaction(function () use ($validated) {

            $produit = Product::findOrFail($validated['produit_id']);

            $validated['user_id'] = Auth::id();

            In::create($validated);


            $ancienneQuantite = $produit->quantite;


            $produit->increment(
                'quantite',
                $validated['quantite']
            );


            $produit->refresh();

            $produit->verifierSeuilStock($ancienneQuantite);


            $auteur = Auth::user();


            Notification::create([
                'title' => 'Nouvelle entrée de stock',
                'message' =>
                "{$auteur->name} a enregistré une entrée de {$validated['quantite']} sur \"{$produit->nom}\".",
                'icon' => 'bi-box-arrow-in-down',
            ]);


            ActivityLog::log(
                'operation',
                "A enregistré une entrée de {$validated['quantite']} sur le produit \"{$produit->nom}\""
            );
        });


        return redirect()
            ->route($this->routePrefix() . '.entrees.index')
            ->with('success', 'Entrée enregistrée avec succès.');
    }



    public function edit(In $entree)
    {
        $produits = Product::with('categorie')
            ->orderBy('nom')
            ->get();


        return view('admin.entrees.edit', compact(
            'entree',
            'produits'
        ));
    }




    public function update(Request $request, In $entree)
    {

        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_entree' => ['required', 'date'],
        ]);



        DB::transaction(function () use ($validated, $entree) {


            $ancienProduit = Product::findOrFail(
                $entree->produit_id
            );


            $nouveauProduit = Product::findOrFail(
                $validated['produit_id']
            );



            /*
             | Si le produit change
             */

            if ($ancienProduit->id !== $nouveauProduit->id) {


                // retirer l'ancienne entrée de l'ancien produit

                if ($ancienProduit->quantite < $entree->quantite) {

                    throw new \Exception(
                        "Impossible de modifier cette entrée : stock insuffisant."
                    );
                }


                $ancienProduit->decrement(
                    'quantite',
                    $entree->quantite
                );



                // ajouter au nouveau produit

                $ancienneQuantite = $nouveauProduit->quantite;


                $nouveauProduit->increment(
                    'quantite',
                    $validated['quantite']
                );


                $nouveauProduit->refresh();


                $nouveauProduit->verifierSeuilStock(
                    $ancienneQuantite
                );
            }


            /*
             | Même produit
             */ else {


                $difference =
                    $validated['quantite'] - $entree->quantite;



                if ($difference > 0) {

                    $nouveauProduit->increment(
                        'quantite',
                        $difference
                    );
                } elseif ($difference < 0) {

                    $nouveauProduit->decrement(
                        'quantite',
                        abs($difference)
                    );
                }
            }



            $entree->update($validated);
        });



        return redirect()
            ->route($this->routePrefix() . '.entrees.index')
            ->with('success', 'Entrée modifiée avec succès.');
    }





    public function destroy(In $entree)
    {

        DB::transaction(function () use ($entree) {


            $produit = $entree->produit;



            if ($produit) {


                if ($produit->quantite < $entree->quantite) {

                    throw new \Exception(
                        "Suppression impossible : stock insuffisant."
                    );
                }


                $produit->decrement(
                    'quantite',
                    $entree->quantite
                );
            }



            $entree->delete();
        });



        return redirect()
            ->route($this->routePrefix() . '.entrees.index')
            ->with('success', 'Entrée supprimée avec succès.');
    }
}
