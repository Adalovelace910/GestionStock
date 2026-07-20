<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sortie;
use App\Models\Produit;
use Illuminate\Http\Request;

class SortieController extends Controller
{
    public function index()
    {
        $sorties = Sortie::with('produit')
            ->latest('date_sortie')
            ->paginate(10);

        return view('admin.sorties.index', compact('sorties'));
    }

    public function create()
    {
        $produits = Produit::orderBy('nom')->get();

        return view('admin.sorties.create', compact('produits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'integer', 'min:1'],
            'date_sortie' => ['required', 'date'],
        ]);

        Sortie::create($validated);

        return redirect()
            ->route('admin.sorties.index')
            ->with('success', 'Sortie enregistrée avec succès.');
    }

    public function edit(Sortie $sortie)
    {
        $produits = Produit::orderBy('nom')->get();

        return view('admin.sorties.edit', compact('sortie', 'produits'));
    }

    public function update(Request $request, Sortie $sortie)
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

    public function destroy(Sortie $sortie)
    {
        $sortie->delete();

        return redirect()
            ->route('admin.sorties.index')
            ->with('success', 'Sortie supprimée avec succès.');
    }
}