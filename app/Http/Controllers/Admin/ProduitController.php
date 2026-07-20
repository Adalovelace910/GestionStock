<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::latest()->paginate(10);

        return view('admin.produits.index', compact('produits'));
    }

    public function create()
    {
        return view('admin.produits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);

        Produit::create($validated);

        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Produit $produit)
    {
        return view('admin.produits.edit', compact('produit'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);

        $produit->update($validated);

        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}