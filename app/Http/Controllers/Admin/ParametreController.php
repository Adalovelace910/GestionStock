<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        $parametre = Parametre::first() ?? Parametre::create([]);

        return view('admin.parametres.index', compact('parametre'));
    }

    public function update(Request $request)
    {
        $parametre = Parametre::first() ?? Parametre::create([]);

        $validated = $request->validate([
            'nom_entreprise' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'seuil_stock_bas' => ['required', 'integer', 'min:0'],
            'unite_mesure_defaut' => ['required', 'string', 'max:50'],
            'elements_par_page' => ['required', 'integer', 'min:5', 'max:100'],
        ]);

        $parametre->update($validated);

        return redirect()
            ->route('admin.parametres.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}