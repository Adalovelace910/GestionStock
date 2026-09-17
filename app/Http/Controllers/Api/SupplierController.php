<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $fournisseurs = Supplier::orderBy('nom')->get();

        return response()->json([
            'fournisseurs' => $fournisseurs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:fournisseurs,email'],
            'adresse' => ['nullable', 'string', 'max:255'],
        ]);

        $fournisseur = Supplier::create($validated);

        return response()->json([
            'message' => 'Fournisseur créé avec succès.',
            'fournisseur' => $fournisseur,
        ], 201);
    }

    public function show(Supplier $fournisseur)
    {
        return response()->json([
            'fournisseur' => $fournisseur,
        ]);
    }

    public function update(Request $request, Supplier $fournisseur)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255', 'unique:fournisseurs,email,' . $fournisseur->id],
            'adresse' => ['nullable', 'string', 'max:255'],
        ]);

        $fournisseur->update($validated);

        return response()->json([
            'message' => 'Fournisseur mis à jour avec succès.',
            'fournisseur' => $fournisseur,
        ]);
    }

    public function destroy(Supplier $fournisseur)
    {
        $fournisseur->delete();

        return response()->json([
            'message' => 'Fournisseur supprimé avec succès.',
        ]);
    }
}
