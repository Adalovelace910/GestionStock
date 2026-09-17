<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('produits')->orderBy('nom')->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom'],
            'description' => ['nullable', 'string'],
        ]);

        $categorie = Category::create($validated);

        return response()->json([
            'message' => 'Catégorie créée avec succès.',
            'categorie' => $categorie,
        ], 201);
    }

    public function show(Category $categorie)
    {
        $categorie->load('produits');

        return response()->json([
            'categorie' => $categorie,
        ]);
    }

    public function update(Request $request, Category $categorie)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:categories,nom,' . $categorie->id],
            'description' => ['nullable', 'string'],
        ]);

        $categorie->update($validated);

        return response()->json([
            'message' => 'Catégorie modifiée avec succès.',
            'categorie' => $categorie,
        ]);
    }

    public function destroy(Category $categorie)
    {
        $categorie->delete();

        return response()->json([
            'message' => 'Catégorie supprimée avec succès.',
        ]);
    }
}
