<?php

namespace App\Http\Controllers\Admin;

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

    private function routePrefix(): string
    {
        return Auth::user()->role === 'admin' ? 'admin' : 'magasinier';
    }


    public function index()
    {
        $sorties = Out::with(['produit.categorie', 'user'])->latest('date_sortie')
            ->paginate(10);

        return view('admin.sorties.index', compact('sorties'));
    }



    public function create()
    {
        $produits = Product::with('categorie')
            ->orderBy('nom')
            ->get();

        return view('admin.sorties.create', compact('produits'));
    }




    public function store(Request $request)
    {

        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_sortie' => ['required', 'date'],
        ]);



        DB::transaction(function () use ($validated) {


            $produit = Product::findOrFail(
                $validated['produit_id']
            );



            if ($validated['quantite'] > $produit->quantite) {

                throw new \Exception(
                    "Stock insuffisant."
                );
            }



            $ancienneQuantite = $produit->quantite;



            $validated['user_id'] = Auth::id();



            Out::create($validated);



            $produit->decrement(
                'quantite',
                $validated['quantite']
            );



            $produit->refresh();



            $produit->verifierSeuilStock(
                $ancienneQuantite
            );




            $auteur = Auth::user();



            Notification::create([
                'title' => 'Nouvelle sortie de stock',
                'message' =>
                "{$auteur->name} a enregistré une sortie de {$validated['quantite']} sur \"{$produit->nom}\".",
                'icon' => 'bi-box-arrow-up',
            ]);



            ActivityLog::log(
                'operation',
                "A enregistré une sortie de {$validated['quantite']} sur le produit \"{$produit->nom}\""
            );
        });



        return redirect()
            ->route($this->routePrefix() . '.sorties.index')
            ->with('success', 'Sortie enregistrée avec succès.');
    }





    public function edit(Out $sortie)
    {

        $produits = Product::with('categorie')
            ->orderBy('nom')
            ->get();



        return view('admin.sorties.edit', compact(
            'sortie',
            'produits'
        ));
    }





    public function update(Request $request, Out $sortie)
    {


        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_sortie' => ['required', 'date'],
        ]);




        DB::transaction(function () use ($validated, $sortie) {



            $ancienProduit = Product::findOrFail(
                $sortie->produit_id
            );



            $nouveauProduit = Product::findOrFail(
                $validated['produit_id']
            );





            /*
             | Changement de produit
             */

            if ($ancienProduit->id !== $nouveauProduit->id) {



                // remettre l'ancienne sortie dans l'ancien stock

                $ancienProduit->increment(
                    'quantite',
                    $sortie->quantite
                );



                // retirer la nouvelle sortie du nouveau produit


                if ($validated['quantite'] > $nouveauProduit->quantite) {


                    throw new \Exception(
                        "Stock insuffisant pour ce produit."
                    );
                }



                $ancienneQuantite =
                    $nouveauProduit->quantite;



                $nouveauProduit->decrement(
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
                    $validated['quantite'] - $sortie->quantite;



                if ($difference > 0) {



                    if ($difference > $nouveauProduit->quantite) {

                        throw new \Exception(
                            "Stock insuffisant."
                        );
                    }



                    $nouveauProduit->decrement(
                        'quantite',
                        $difference
                    );
                } elseif ($difference < 0) {



                    $nouveauProduit->increment(
                        'quantite',
                        abs($difference)
                    );
                }
            }




            $sortie->update($validated);
        });




        return redirect()
            ->route($this->routePrefix() . '.sorties.index')
            ->with('success', 'Sortie modifiée avec succès.');
    }





    public function destroy(Out $sortie)
    {


        DB::transaction(function () use ($sortie) {


            $produit = $sortie->produit;



            if ($produit) {


                $produit->increment(
                    'quantite',
                    $sortie->quantite
                );
            }



            $sortie->delete();
        });



        return redirect()
            ->route($this->routePrefix() . '.sorties.index')
            ->with('success', 'Sortie supprimée avec succès.');
    }
}
