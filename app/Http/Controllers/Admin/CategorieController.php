<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.categories.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s\'\-]*$/'],
            'description' => ['nullable', 'string', 'regex:/^[A-Za-zÀ-ÿ]/'],
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.regex' => 'Le nom ne doit contenir aucun chiffre ni caractère spécial, uniquement des lettres.',
            'description.regex' => 'La description doit commencer par une lettre (pas un chiffre ni un caractère spécial).',
        ]);


        Category::create($validated);


        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie ajoutée avec succès.');
    }


    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'categorie' => $category
        ]);
    }


    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s\'\-]*$/'],
            'description' => ['nullable', 'string', 'regex:/^[A-Za-zÀ-ÿ]/'],
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.regex' => 'Le nom ne doit contenir aucun chiffre ni caractère spécial, uniquement des lettres.',
            'description.regex' => 'La description doit commencer par une lettre (pas un chiffre ni un caractère spécial).',
        ]);


        $category->update($validated);


        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }


    public function destroy(Category $category)
    {
        $category->delete();


        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}