@extends('layouts.app')
@section('title','Paramètres')
@section('page-title','Paramètres système')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
<h2 class="fw-bold mb-0">Paramètres système</h2>
<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
<i class="bi bi-arrow-left me-1"></i>
Retour
</a>
</div>
<div class="card shadow-sm border-0">
<div class="card-body">
@if($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
<form action="{{ route('admin.parametres.update') }}" method="POST">
@csrf
@method('PUT')
<h5 class="mb-3">Informations de l'entreprise</h5>
<div class="mb-3">
<label class="form-label">Nom de l'entreprise</label>
<input type="text" name="nom_entreprise" value="{{ old('nom_entreprise',$parametre->nom_entreprise) }}" class="form-control">
</div>
<div class="mb-3">
<label class="form-label">Adresse</label>
<input type="text" name="adresse" value="{{ old('adresse',$parametre->adresse) }}" class="form-control">
</div>
<div class="mb-4">
<label class="form-label">Téléphone</label>
<input type="text" name="telephone" value="{{ old('telephone',$parametre->telephone) }}" class="form-control">
</div>
<hr>
<h5 class="mb-3 mt-4">Gestion du stock</h5>
<div class="mb-3">
<label class="form-label">Seuil d'alerte stock bas</label>
<input type="number" name="seuil_stock_bas" min="0" value="{{ old('seuil_stock_bas',$parametre->seuil_stock_bas) }}" class="form-control">
</div>
<div class="mb-4">
<label class="form-label">Destinataires des alertes email</label>
<textarea name="emails_alertes" rows="4" class="form-control" placeholder="Un email par ligne">{{ old('emails_alertes',$parametre->emails_alertes) }}</textarea>
</div>
<div class="mb-4">
<label class="form-label">Unité de mesure par défaut</label>
<input type="text" name="unite_mesure_defaut" value="{{ old('unite_mesure_defaut',$parametre->unite_mesure_defaut) }}" class="form-control">
</div>
<hr>
<h5 class="mb-3 mt-4">Règles de production</h5>
<p class="text-muted">Définissez ici les quantités réellement obtenues pour chaque matière première.<br><strong>Aucun chiffre n'est imposé par l'application.</strong></p>
<div class="alert alert-light border">Exemple de logique : <strong>1 kg → quantité de sacs obtenus</strong> selon la règle que vous définissez.</div>
<div id="regles-container">
@foreach($regles as $index => $regle)
<div class="row g-2 align-items-end mb-3 regle-row">
<div class="col-md-4">
<label class="form-label">Matière première</label>
<select name="regles[{{ $index }}][matiere_premiere_id]" class="form-select" required>
<option value="">-- Choisir --</option>
@foreach($matieresPremieres as $matiere)
<option value="{{ $matiere->id }}" @selected($regle->matiere_premiere_id == $matiere->id)>{{ $matiere->nom }}</option>
@endforeach
</select>
</div>
<div class="col-md-4">
<label class="form-label">Produit / farine</label>
<select name="regles[{{ $index }}][produit_id]" class="form-select" required>
<option value="">-- Choisir --</option>
@foreach($produits as $produit)
<option value="{{ $produit->id }}" @selected($regle->produit_id == $produit->id)>{{ $produit->nom }} ({{ $produit->categorie->nom ?? 'sans catégorie' }})</option>
@endforeach
</select>
</div>
<div class="col-md-3">
<label class="form-label">Sacs obtenus pour 1 kg</label>
<input type="number" name="regles[{{ $index }}][quantite_par_kg]" class="form-control" min="0" step="0.0001" value="{{ $regle->quantite_par_kg }}" required>
</div>
<div class="col-md-1">
<button type="button" class="btn btn-outline-danger w-100 supprimer-regle"><i class="bi bi-trash"></i></button>
</div>
</div>
@endforeach
</div>
<button type="button" id="ajouter-regle" class="btn btn-outline-primary mb-4"><i class="bi bi-plus-lg me-1"></i>Ajouter une règle</button>
<hr>
<h5 class="mb-3 mt-4">Affichage</h5>
<div class="mb-4">
<label class="form-label">Éléments par page</label>
<input type="number" name="elements_par_page" min="5" max="100" value="{{ old('elements_par_page',$parametre->elements_par_page) }}" class="form-control">
</div>
<button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>
</form>
</div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
const container=document.getElementById('regles-container');
const ajouter=document.getElementById('ajouter-regle');
let index=container.querySelectorAll('.regle-row').length;
ajouter.addEventListener('click',function(){
const ligne=document.createElement('div');
ligne.className='row g-2 align-items-end mb-3 regle-row';
ligne.innerHTML=`
<div class="col-md-4">
<label class="form-label">Matière première</label>
<select name="regles[${index}][matiere_premiere_id]" class="form-select" required>
<option value="">-- Choisir --</option>
@foreach($matieresPremieres as $matiere)
<option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
@endforeach
</select>
</div>
<div class="col-md-4">
<label class="form-label">Produit / farine</label>
<select name="regles[${index}][produit_id]" class="form-select" required>
<option value="">-- Choisir --</option>
@foreach($produits as $produit)
<option value="{{ $produit->id }}">{{ $produit->nom }} ({{ $produit->categorie->nom ?? 'sans catégorie' }})</option>
@endforeach
</select>
</div>
<div class="col-md-3">
<label class="form-label">Sacs obtenus pour 1 kg</label>
<input type="number" name="regles[${index}][quantite_par_kg]" class="form-control" min="0" step="0.0001" value="0" required>
</div>
<div class="col-md-1">
<button type="button" class="btn btn-outline-danger w-100 supprimer-regle"><i class="bi bi-trash"></i></button>
</div>
`;
container.appendChild(ligne);
index++;
});
container.addEventListener('click',function(event){
const bouton=event.target.closest('.supprimer-regle');
if(!bouton){return;}
bouton.closest('.regle-row').remove();
});
});
</script>
@endpush
@endsection