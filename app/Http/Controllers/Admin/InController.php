<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\In;
use App\Models\Product;
use Illuminate\Http\Request;

class InController extends Controller
{
    public function index()
    {
        $entrees = In::with('produit')
            ->latest('date_entree')
            ->paginate(10);

        return view('admin.entrees.index', compact('entrees'));
    }

    public function create()
    {
        $produits = Product::orderBy('nom')->get();

        return view('admin.entrees.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_entree' => ['required', 'date'],
        ]);

        In::create($validated);

        return redirect()
            ->route('admin.entrees.index')
            ->with('success', 'Entrée enregistrée avec succès.');
    }

    public function edit(In $entree)
    {
        $produits = Product::orderBy('nom')->get();

        return view('admin.entrees.edit', compact('entree', 'produits'));
    }

    public function update(Request $request, In $entree)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_entree' => ['required', 'date'],
        ]);

        $entree->update($validated);

        return redirect()
            ->route('admin.entrees.index')
            ->with('success', 'Entrée modifiée avec succès.');
    }

    public function destroy(In $entree)
    {
        $entree->delete();

        return redirect()
            ->route('admin.entrees.index')
            ->with('success', 'Entrée supprimée avec succès.');
    }
}