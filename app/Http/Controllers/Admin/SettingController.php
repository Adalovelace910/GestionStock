<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MatierePremiere;
use App\Models\Product;
use App\Models\RegleProduction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SettingController extends Controller
{
public function index()
{
$parametre = Setting::first() ?? Setting::create([]);
$matieresPremieres = MatierePremiere::orderBy('nom')->get();
$produits = Product::with('categorie')->orderBy('nom')->get();
$regles = RegleProduction::with(['matierePremiere', 'produit.categorie'])->orderBy('matiere_premiere_id')->orderBy('produit_id')->get();
return view('admin.parametres.index', compact('parametre', 'matieresPremieres', 'produits', 'regles'));
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
if (!empty($validated['emails_alertes'])) {
$emails = collect(preg_split('/[\r\n,]+/', $validated['emails_alertes']))->map(fn($email) => trim($email))->filter();
foreach ($emails as $email) {
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
return back()->withErrors(['emails_alertes' => "\"{$email}\" n'est pas une adresse email valide."])->withInput();
}
}
}
$reglesInput = $request->input('regles', []);
if (!is_array($reglesInput)) {
$reglesInput = [];
}
$reglesInput = collect($reglesInput)->filter(function ($regle) {
return filled($regle['matiere_premiere_id'] ?? null) || filled($regle['produit_id'] ?? null) || filled($regle['quantite_par_kg'] ?? null);
})->values()->all();
$reglesValidees = validator(
['regles' => $reglesInput],
[
'regles' => ['nullable', 'array'],
'regles.*.matiere_premiere_id' => ['required', 'exists:matieres_premieres,id'],
'regles.*.produit_id' => ['required', 'exists:produits,id'],
'regles.*.quantite_par_kg' => ['required', 'numeric', 'min:0'],
]
)->validate();
$combinaisons = [];
foreach ($reglesValidees['regles'] ?? [] as $regle) {
$cle = $regle['matiere_premiere_id'] . '-' . $regle['produit_id'];
if (isset($combinaisons[$cle])) {
return back()->withErrors(['regles' => 'Une même matière première et un même produit ne peuvent être enregistrés qu’une seule fois.'])->withInput();
}
$combinaisons[$cle] = true;
}
DB::transaction(function () use ($parametre, $validated, $reglesValidees) {
$parametre->update($validated);
RegleProduction::query()->delete();
foreach ($reglesValidees['regles'] ?? [] as $regle) {
RegleProduction::create([
'matiere_premiere_id' => $regle['matiere_premiere_id'],
'produit_id' => $regle['produit_id'],
'quantite_par_kg' => $regle['quantite_par_kg'],
]);
}
});
return redirect()->route('admin.parametres.index')->with('success', 'Paramètres et règles de production mis à jour avec succès.');
}
}