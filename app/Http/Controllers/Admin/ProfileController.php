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

        return redirect()
            ->route('admin.profil.edit')
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
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
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

        return redirect()
            ->route('admin.profil.password.edit')
            ->with('success', 'Mot de passe modifié avec succès.');
    }
}