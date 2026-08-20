<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $utilisateurs = User::latest()->paginate(10);

        return view('admin.utilisateurs.index', compact('utilisateurs'));
    }

    public function create()
    {
        return view('admin.utilisateurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s\'\-]*$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-zÀ-ÿ])(?=.*\d).+$/'],
            'role' => ['required', 'in:admin,magasinier'],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.regex' => 'Le nom ne doit contenir aucun chiffre ni caractère spécial, uniquement des lettres.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
            'role.required' => 'Veuillez choisir un rôle.',
            'role.in' => 'Le rôle sélectionné est invalide.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $utilisateur)
    {
        return view('admin.utilisateurs.edit', compact('utilisateur'));
    }

    public function update(Request $request, User $utilisateur)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÀ-ÿ][A-Za-zÀ-ÿ\s\'\-]*$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($utilisateur->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-zÀ-ÿ])(?=.*\d).+$/'],
            'role' => ['required', 'in:admin,magasinier'],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.regex' => 'Le nom ne doit contenir aucun chiffre ni caractère spécial, uniquement des lettres.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
            'role.required' => 'Veuillez choisir un rôle.',
            'role.in' => 'Le rôle sélectionné est invalide.',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $utilisateur->update($validated);

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    public function destroy(User $utilisateur)
    {
        $utilisateur->delete();

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}