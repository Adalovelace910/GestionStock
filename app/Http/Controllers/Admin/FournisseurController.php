<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier as Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::latest()->paginate(10);

        return view('admin.fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('admin.fournisseurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/'],
            'telephone' => ['nullable', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:255', 'unique:fournisseurs,email'],
            'adresse' => ['nullable', 'string', 'max:255', 'regex:/^(?!\d).+$/'],
        ], [
            'nom.required' => 'Le nom du fournisseur est obligatoire.',
            'nom.regex' => 'Le nom doit commencer par une lettre (pas un chiffre ni un caractère spécial).',
            'telephone.regex' => 'Le téléphone ne doit contenir que des chiffres, espaces, "+" ou "-" (8 à 20 caractères).',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé par un autre fournisseur.',
            'adresse.regex' => 'L\'adresse ne doit pas commencer par un chiffre.',
        ]);

        Fournisseur::create($validated);

        return redirect()
            ->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur créé avec succès.');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('admin.fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ0-9\s\'\-]*$/'],
            'telephone' => ['nullable', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email' => ['nullable', 'email', 'max:255', 'unique:fournisseurs,email,' . $fournisseur->id],
            'adresse' => ['nullable', 'string', 'max:255', 'regex:/^(?!\d).+$/'],
        ], [
            'nom.required' => 'Le nom du fournisseur est obligatoire.',
            'nom.regex' => 'Le nom doit commencer par une lettre (pas un chiffre ni un caractère spécial).',
            'telephone.regex' => 'Le téléphone ne doit contenir que des chiffres, espaces, "+" ou "-" (8 à 20 caractères).',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé par un autre fournisseur.',
            'adresse.regex' => 'L\'adresse ne doit pas commencer par un chiffre.',
        ]);

        $fournisseur->update($validated);

        return redirect()
            ->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur modifié avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()
            ->route('admin.fournisseurs.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
}