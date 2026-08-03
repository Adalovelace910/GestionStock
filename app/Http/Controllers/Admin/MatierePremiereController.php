<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MatierePremiere;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatierePremiereController extends Controller
{
    public function index()
    {
        $matieresPremieres = MatierePremiere::orderBy('nom')
            ->paginate(10);

        return view('admin.matierespremieres.index', compact('matieresPremieres'));
    }

    public function create()
    {
        return view('admin.matierespremieres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/',
                Rule::unique('matieres_premieres', 'nom'),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantite' => ['required', 'integer', 'min:1'],
            'prix' => ['required', 'numeric', 'min:0'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom doit commencer par une lettre.',
            'nom.unique' => 'Cette matière première existe déjà.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité initiale doit être d\'au moins 1.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
        ]);

        $matierePremiere = MatierePremiere::create($validated);

        Notification::create([
            'title' => 'Nouvelle matière première',
            'message' => "La matière première \"{$matierePremiere->nom}\" a été ajoutée.",
            'icon' => 'bi-box-seam',
        ]);

        ActivityLog::log(
            'operation',
            "A créé la matière première \"{$matierePremiere->nom}\""
        );

        return redirect()
            ->route('admin.matieres-premieres.index')
            ->with('success', 'Matière première ajoutée avec succès.');
    }

    public function edit(MatierePremiere $matieresPremiere)
    {
        return view('admin.matierespremieres.edit', [
            'matierePremiere' => $matieresPremiere,
        ]);
    }

    public function update(Request $request, MatierePremiere $matieresPremiere)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/',
                Rule::unique('matieres_premieres', 'nom')->ignore($matieresPremiere->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'quantite' => ['required', 'integer', 'min:0'],
            'prix' => ['required', 'numeric', 'min:0'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom doit commencer par une lettre.',
            'nom.unique' => 'Cette matière première existe déjà.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité ne peut pas être négative.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
        ]);

        $matieresPremiere->update($validated);

        ActivityLog::log(
            'operation',
            "A modifié la matière première \"{$matieresPremiere->nom}\""
        );

        return redirect()
            ->route('admin.matieres-premieres.index')
            ->with('success', 'Matière première modifiée avec succès.');
    }

    public function destroy(MatierePremiere $matieresPremiere)
    {
        $nom = $matieresPremiere->nom;

        $matieresPremiere->delete();

        ActivityLog::log(
            'operation',
            "A supprimé la matière première \"{$nom}\""
        );

        return redirect()
            ->route('admin.matieres-premieres.index')
            ->with('success', 'Matière première supprimée avec succès.');
    }
}