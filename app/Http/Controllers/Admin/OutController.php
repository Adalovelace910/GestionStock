<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Out;
use App\Models\Product;
use Illuminate\Http\Request;

class OutController extends Controller
{
    public function index()
    {
        $sorties = Out::with('produit')
            ->latest('date_sortie')
            ->paginate(10);

        return view('admin.sorties.index', compact('sorties'));
    }

    public function create()
    {
        $produits = Product::orderBy('nom')->get();

        return view('admin.sorties.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_sortie' => ['required', 'date'],
        ]);

        Out::create($validated);

        return redirect()
            ->route('admin.sorties.index')
            ->with('success', 'Sortie enregistrée avec succès.');
    }

    public function edit(Out $sortie)
    {
        $produits = Product::orderBy('nom')->get();

        return view('admin.sorties.edit', compact('sortie', 'produits'));
    }

    public function update(Request $request, Out $sortie)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_sortie' => ['required', 'date'],
        ]);

        $sortie->update($validated);

        return redirect()
            ->route('admin.sorties.index')
            ->with('success', 'Sortie modifiée avec succès.');
    }

    public function destroy(Out $sortie)
    {
        $sortie->delete();

        return redirect()
            ->route('admin.sorties.index')
            ->with('success', 'Sortie supprimée avec succès.');
    }
}