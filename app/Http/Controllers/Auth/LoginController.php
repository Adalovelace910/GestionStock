<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            ActivityLog::log('connexion', 'Connexion réussie (' . Auth::user()->email . ')');

            if (Auth::user()->role == 'admin') {

                return redirect()
                    ->route('admin.dashboard');
            }
            if (Auth::user()->role == 'magasinier') {

                return redirect()
                    ->route('magasinier.dashboard');
            }
            return redirect('/login');
        }


        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ]);
    }
    public function destroy(Request $request)
    {

        ActivityLog::log('connexion', 'Déconnexion (' . (Auth::user()->email ?? '—') . ')');

        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect()
            ->route('login');
    }
}