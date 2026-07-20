<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entree;
use App\Models\Produit;
use Illuminate\Http\Request;

class EntreeController extends Controller
{
    public function index()
    {
        $entrees = Entree::with('produit')
            ->latest('date_entree')
            ->paginate(10);

        return view('admin.entrees.index', compact('entrees'));
    }

    public function create()
    {
        $produits = Produit::orderBy('nom')->get();

        return view('admin.entrees.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_entree' => ['required', 'date'],
        ]);

        Entree::create($validated);

        return redirect()
            ->route('admin.entrees.index')
            ->with('success', 'Entrée enregistrée avec succès.');
    }

    public function edit(Entree $entree)
    {
        $produits = Produit::orderBy('nom')->get();

        return view('admin.entrees.edit', compact('entree', 'produits'));
    }

    public function update(Request $request, Entree $entree)
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

    public function destroy(Entree $entree)
    {
        $entree->delete();

        return redirect()
            ->route('admin.entrees.index')
            ->with('success', 'Entrée supprimée avec succès.');
    }
}