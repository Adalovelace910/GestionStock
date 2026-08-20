<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
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


    public function index()
    {
        $produits = Product::with('categorie')
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('nom')->get();
        $seuil = $this->seuilStockBas();
        return view('admin.produits.index', compact('produits', 'categories', 'seuil'));
    }



    public function create()
    {
        $categories = Category::orderBy('nom')->get();
        $seuil = $this->seuilStockBas();
        return view('admin.produits.create', compact('categories', 'seuil'));
    }




    public function store(Request $request)
    {
        $seuil = $this->seuilStockBas();

        $validated = $request->validate([

            'nom' => [
                        'required',
                        'string',
                        'max:255',
                        'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/',
                        Rule::unique('produits', 'nom')->where(function ($query) use ($request) {
                            return $query->where('categorie_id', $request->categorie_id);
                        }),
                    ],

            'description' => ['string', 'max:1000' ],

            'categorie_id' => [
                'required',
                'exists:categories,id'
            ],

            'quantite' => [
                'required',
                'integer',
                "min:{$seuil}"
            ],

            'prix' => [
                'required',
                'numeric',
                'min:0.01'
            ],

        ],[

            'nom.required' => 'Le nom du produit est obligatoire.',
            'nom.regex' => 'Le nom doit commencer par une lettre.',
            'nom.unique' => 'Il existe déjà un produit portant ce nom dans cette catégorie.',
            'categorie_id.required' => 'Veuillez choisir une catégorie.',
            'categorie_id.exists' => 'La catégorie est invalide.',

            'matiere_premiere_id.unique' => 'Cette matière première est déjà liée à un autre produit.',

            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => "La quantité initiale doit être d'au moins {$seuil} (seuil de stock bas configuré dans les paramètres).",

            'prix.required' => 'Le prix est obligatoire.',
            'prix.min' => 'Le prix doit être supérieur à 0.',

        ]);



        $produit = Product::create($validated);



        $produit->verifierSeuilStock(PHP_INT_MAX);



        Notification::create([

            'title' => 'Nouveau produit',

            'message' =>
            "Le produit \"{$produit->nom}\" a été ajouté au stock.",

            'icon' => 'bi-box-seam',

        ]);

        ActivityLog::log(
            'operation',
            "A créé le produit \"{$produit->nom}\""
        );
        return redirect()
            ->route('admin.produits.index')
            ->with(
                'success',
                'Produit créé avec succès.'
            );

    }






    public function edit(Product $produit)
    {

        $categories = Category::orderBy('nom')->get();

        return view(
            'admin.produits.edit',
            compact(
                'produit',
                'categories'
            )
        );

    }






    public function update(Request $request, Product $produit)
    {

        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/',
                Rule::unique('produits', 'nom')
                    ->where(function ($query) use ($request) {
                        return $query->where('categorie_id', $request->categorie_id);
                    })
                    ->ignore($produit->id),
            ],

            'description' => [
                'required',
                'string',
                'max:1000',
                'regex:/^[A-Za-zÀ-ÿ]/'
            ],

            'categorie_id' => [
                'required',
                'exists:categories,id'
            ],

            'quantite' => [
                'required',
                'integer',
                'min:1'
            ],

            'prix' => [
                'required',
                'numeric',
                'min:0.01'
            ],

        ], [

            'nom.required' => 'Le nom du produit est obligatoire.',
            'nom.regex' => 'Le nom doit commencer par une lettre.',
            'nom.unique' => 'Il existe déjà un produit portant ce nom dans cette catégorie.',
            'matiere_premiere_id.unique' => 'Cette matière première est déjà liée à un autre produit.',

        ]);





        $ancienneQuantite =
            $produit->quantite;



        $produit->update($validated);




        $produit->verifierSeuilStock(
            $ancienneQuantite
        );





        ActivityLog::log(
            'operation',
            "A modifié le produit \"{$produit->nom}\""
        );





        return redirect()
            ->route('admin.produits.index')
            ->with(
                'success',
                'Produit modifié avec succès.'
            );

    }







    public function destroy(Product $produit)
    {


        $nom = $produit->nom;


        $produit->delete();



        ActivityLog::log(
            'operation',
            "A supprimé le produit \"{$nom}\""
        );




        return redirect()
            ->route('admin.produits.index')
            ->with(
                'success',
                'Produit supprimé avec succès.'
            );

    }

}