<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        return view('admin.profil.edit', compact('utilisateur'));
    }

    public function update(Request $request)
    {
        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($utilisateur->id)],
        ]);

        $utilisateur->update($validated);

        ActivityLog::log('operation', 'A modifié son profil (' . $utilisateur->name . ')');

        $prefix = $utilisateur->role === 'admin' ? 'admin' : 'magasinier';

        return redirect()
            ->route("{$prefix}.profil.edit")
            ->with('success', 'Profil mis à jour avec succès.');
    }

    public function editPassword()
    {
        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        return view('admin.profil.password', compact('utilisateur'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
                'regex:/^(?=.*[A-Za-zÀ-ÿ])(?=.*\d).+$/',
            ],
        ], [
            'current_password.required' => 'Veuillez saisir votre mot de passe actuel.',
            'password.required' => 'Veuillez saisir un nouveau mot de passe.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation ne correspond pas au nouveau mot de passe.',
            'password.different' => 'Le nouveau mot de passe doit être différent de l\'ancien.',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
        ]);

        /** @var User $utilisateur */
        $utilisateur = Auth::user();

        if (! Hash::check($validated['current_password'], $utilisateur->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $utilisateur->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::log('operation', 'A changé son mot de passe');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Mot de passe modifié avec succès. Veuillez vous reconnecter.');
    }
}