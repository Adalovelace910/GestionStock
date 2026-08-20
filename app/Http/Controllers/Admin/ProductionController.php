<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\In;
use App\Models\MatierePremiere;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Production;
use App\Models\RegleProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ProductionController extends Controller
{
private function routePrefix(): string
{
return Auth::user()->role === 'admin' ? 'admin' : 'magasinier';
}

public function index()
{
$productions = Production::with(['matierePremiere', 'user'])->latest('date_production')->paginate(20);
return view('admin.production.index', compact('productions'));
}

public function create(Request $request)
{
$matieresPremieres = MatierePremiere::orderBy('nom')->get();
$regles = RegleProduction::with('produit')->orderBy('matiere_premiere_id')->orderBy('produit_id')->get()->groupBy('matiere_premiere_id')->map(function ($rules) {
return $rules->map(function ($rule) {
return ['produit_id' => $rule->produit_id, 'produit_nom' => $rule->produit->nom, 'quantite_par_kg' => (float) $rule->quantite_par_kg];
})->values();
});
$matierePremiereSelectionnee = $request->query('matiere_premiere_id');
return view('admin.production.create', compact('matieresPremieres', 'regles', 'matierePremiereSelectionnee'));
}

public function store(Request $request)
{
$validated = $request->validate([
'matiere_premiere_id' => ['required', 'exists:matieres_premieres,id'],
'quantite_matiere_premiere' => ['required', 'numeric', 'min:0.01'],
'date_production' => ['required', 'date'],
], [
'matiere_premiere_id.required' => 'Veuillez choisir une matière première.',
'matiere_premiere_id.exists' => 'La matière première choisie est invalide.',
'quantite_matiere_premiere.required' => 'Veuillez saisir la quantité utilisée.',
'quantite_matiere_premiere.numeric' => 'La quantité doit être un nombre.',
'quantite_matiere_premiere.min' => 'La quantité doit être supérieure à 0 kg.',
'date_production.required' => 'La date de production est obligatoire.',
'date_production.date' => 'La date de production est invalide.',
]);

$quantiteMatiere = (float) $validated['quantite_matiere_premiere'];

$regles = RegleProduction::with('produit')->where('matiere_premiere_id', $validated['matiere_premiere_id'])->get();

if ($regles->isEmpty()) {
return back()->withErrors(['matiere_premiere_id' => 'Aucune règle de production n’est configurée pour cette matière première. Allez dans Paramètres.'])->withInput();
}

$resultats = $regles->map(function ($regle) use ($quantiteMatiere) {
return ['produit_id' => $regle->produit_id, 'produit_nom' => $regle->produit->nom, 'quantite' => round($quantiteMatiere * (float) $regle->quantite_par_kg, 2)];
})->filter(fn($resultat) => $resultat['quantite'] > 0)->values();

if ($resultats->isEmpty()) {
return back()->withErrors(['quantite_matiere_premiere' => 'Cette quantité ne produit aucun produit avec les règles configurées.'])->withInput();
}

$matiereNom = '';
$resume = '';
$stocksAvantProduction = [];

DB::transaction(function () use ($validated, $quantiteMatiere, $resultats, &$matiereNom, &$resume, &$stocksAvantProduction) {
$matierePremiere = MatierePremiere::lockForUpdate()->findOrFail($validated['matiere_premiere_id']);
$stockMatiere = (float) $matierePremiere->quantite;

if ($quantiteMatiere > $stockMatiere) {
throw ValidationException::withMessages(['quantite_matiere_premiere' => "Stock insuffisant : il reste " . $stockMatiere . " kg de \"" . $matierePremiere->nom . "\"."]);
}

$matiereNom = $matierePremiere->nom;

$production = Production::create([
'matiere_premiere_id' => $matierePremiere->id,
'quantite_matiere_premiere' => $quantiteMatiere,
'date_production' => $validated['date_production'],
'user_id' => Auth::id(),
]);

$matierePremiere->decrement('quantite', $quantiteMatiere);

foreach ($resultats as $resultat) {
$produit = Product::lockForUpdate()->findOrFail($resultat['produit_id']);
$stocksAvantProduction[$produit->id] = (float) $produit->quantite;

In::create([
'produit_id' => $produit->id,
'user_id' => Auth::id(),
'production_id' => $production->id,
'matiere_premiere_id' => null,
'quantite_matiere_premiere' => null,
'rendement' => null,
'quantite' => $resultat['quantite'],
'date_entree' => $validated['date_production'],
]);

$produit->increment('quantite', $resultat['quantite']);
}

$resume = $resultats->map(function ($resultat) {
return $resultat['produit_nom'] . ' : ' . $this->formatNumber($resultat['quantite']) . ' sac(s)';
})->implode(', ');
});

foreach ($stocksAvantProduction as $produitId => $ancienneQuantite) {
$produit = Product::find($produitId);
if ($produit) {
$produit->refresh();
$produit->verifierSeuilStock($ancienneQuantite);
}
}

$auteur = Auth::user();

Notification::create([
'title' => 'Production enregistrée',
'message' => "{$auteur->name} a utilisé " . $this->formatNumber($quantiteMatiere) . " kg de \"" . $matiereNom . "\". Résultats : " . $resume . ".",
'icon' => 'bi-gear-wide-connected',
]);

ActivityLog::log('operation', "A enregistré une production de " . $this->formatNumber($quantiteMatiere) . " kg de \"" . $matiereNom . "\". Résultats : " . $resume . ".");

return redirect()->route($this->routePrefix() . '.entrees.index')->with('success', 'Production enregistrée avec succès. Les produits fabriqués ont été ajoutés automatiquement aux entrées et au stock.');
}

public function edit(Production $production)
{
$production->load(['matierePremiere', 'entrees.produit']);
return view('admin.production.edit', compact('production'));
}

public function update(Request $request, Production $production)
{
$validated = $request->validate([
'date_production' => ['required', 'date'],
], [
'date_production.required' => 'La date de production est obligatoire.',
'date_production.date' => 'La date de production est invalide.',
]);

$production->update($validated);

ActivityLog::log('operation', "A modifié la date d'une production de " . $this->formatNumber((float) $production->quantite_matiere_premiere) . " kg de \"" . $production->matierePremiere->nom . "\".");

return redirect()->route($this->routePrefix() . '.production.index')->with('success', 'Date de production mise à jour avec succès.');
}

public function destroy(Production $production)
{
$production->load('entrees');
$matiereNom = '';
$quantiteMatiere = 0.0;

try {
DB::transaction(function () use ($production, &$matiereNom, &$quantiteMatiere) {
$matierePremiere = MatierePremiere::lockForUpdate()->findOrFail($production->matiere_premiere_id);
$matiereNom = $matierePremiere->nom;
$quantiteMatiere = (float) $production->quantite_matiere_premiere;

$produitsVerrouilles = [];
foreach ($production->entrees as $entree) {
$produit = Product::lockForUpdate()->findOrFail($entree->produit_id);
if ((float) $entree->quantite > (float) $produit->quantite) {
throw new \Exception("Impossible de supprimer : le stock de \"" . $produit->nom . "\" est inférieur à la quantité produite (" . $this->formatNumber((float) $entree->quantite) . "). Une partie a probablement déjà été vendue.");
}
$produitsVerrouilles[$entree->id] = $produit;
}

$matierePremiere->increment('quantite', $quantiteMatiere);

foreach ($production->entrees as $entree) {
$produit = $produitsVerrouilles[$entree->id];
$produit->decrement('quantite', $entree->quantite);
$entree->delete();
}

$production->delete();
});
} catch (\Exception $e) {
return back()->with('error', $e->getMessage());
}

ActivityLog::log('operation', "A supprimé une production de " . $this->formatNumber($quantiteMatiere) . " kg de \"" . $matiereNom . "\" (matière première restaurée, produits retirés du stock).");

return redirect()->route($this->routePrefix() . '.production.index')->with('success', 'Production supprimée avec succès. La matière première a été restaurée et les produits fabriqués retirés du stock.');
}

private function formatNumber(float $value): string
{
$formatted = number_format($value, 2, ',', ' ');
return rtrim(rtrim($formatted, '0'), ',');
}
}