<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatierePremiere;
use Illuminate\Http\Request;

class MatierePremiereController extends Controller
{
    public function index()
    {
        $matieres = MatierePremiere::orderBy('nom')->get();

        return response()->json([
            'matieres_premieres' => $matieres,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:matieres_premieres,nom'],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantite' => ['required', 'numeric', 'min:0'],
            'date_ajout' => ['required', 'date'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);

        $matiere = MatierePremiere::create($validated);

        return response()->json([
            'message' => 'Matière première créée avec succès.',
            'matiere_premiere' => $matiere,
        ], 201);
    }

    public function show(MatierePremiere $matierePremiere)
    {
        return response()->json([
            'matiere_premiere' => $matierePremiere,
        ]);
    }

    public function update(Request $request, MatierePremiere $matierePremiere)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:matieres_premieres,nom,' . $matierePremiere->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantite' => ['required', 'numeric', 'min:0'],
            'date_ajout' => ['required', 'date'],
            'prix' => ['required', 'numeric', 'min:0'],
        ]);

        $matierePremiere->update($validated);

        return response()->json([
            'message' => 'Matière première modifiée avec succès.',
            'matiere_premiere' => $matierePremiere,
        ]);
    }

    public function destroy(MatierePremiere $matierePremiere)
    {
        $matierePremiere->delete();

        return response()->json([
            'message' => 'Matière première supprimée avec succès.',
        ]);
    }
}
