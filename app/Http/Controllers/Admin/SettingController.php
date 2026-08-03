<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $parametre = Setting::first() ?? Setting::create([]);

        return view('admin.parametres.index', compact('parametre'));
    }

    public function update(Request $request)
    {
        $parametre = Setting::first() ?? Setting::create([]);

        $validated = $request->validate([
            'nom_entreprise' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'seuil_stock_bas' => ['required', 'integer', 'min:0'],
            'emails_alertes' => ['nullable', 'string'],
            'unite_mesure_defaut' => ['required', 'string', 'max:50'],
            'elements_par_page' => ['required', 'integer', 'min:5', 'max:100'],
        ]);

        // Validation ligne par ligne des emails saisis
        if (! empty($validated['emails_alertes'])) {

            $emails = collect(preg_split('/[\r\n,]+/', $validated['emails_alertes']))
                ->map(fn ($email) => trim($email))
                ->filter();

            foreach ($emails as $email) {
                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return back()
                        ->withErrors(['emails_alertes' => "\"{$email}\" n'est pas une adresse email valide."])
                        ->withInput();
                }
            }
        }

        $parametre->update($validated);

        return redirect()
            ->route('admin.parametres.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}