<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $produits = Product::with('categorie')
            ->latest()
            ->paginate(10);

        return view('admin.produits.index', compact('produits'));
    }



    public function create()
    {
        $categories = Category::orderBy('nom')->get();

        return view('admin.produits.create', compact('categories'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);


        $produit = Product::create($validated);


        Notification::create([
            'title' => 'Nouveau produit',
            'message' => "Le produit \"{$produit->nom}\" a été ajouté au stock.",
            'icon' => 'bi-box-seam',
        ]);


        ActivityLog::log(
            'operation',
            "A créé le produit \"{$produit->nom}\""
        );


        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }




    public function edit(Product $produit)
    {
        $categories = Category::orderBy('nom')->get();


        return view('admin.produits.edit', compact(
            'produit',
            'categories'
        ));
    }




    public function update(Request $request, Product $produit)
    {

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);



        $produit->update($validated);



        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit modifié avec succès.');
    }




    public function destroy(Product $produit)
    {

        $produit->delete();


        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

}