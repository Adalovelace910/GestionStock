@extends('layouts.app')
@section('title','Matières premières')
@section('page-title','Matières premières')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
<h5 class="mb-0">Matières premières</h5>
<a href="{{ route('admin.matieres-premieres.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Ajouter</a>
</div>
<div class="card shadow-sm border-0">
<div class="card-body">
@if($matieresPremieres->isEmpty())
<p class="text-muted mb-0">Aucune matière première enregistrée.</p>
@else
<div class="table-responsive">
<table id="matieresPremieresTable" class="table table-bordered table-hover align-middle mb-0">
<thead class="table-light">
<tr>
<th>Date d'ajout</th>
<th>Nom</th>
<th>Unité</th>
<th class="text-end">Actions</th>
</tr>
</thead>
<tbody>
@foreach($matieresPremieres as $matiere)
<tr>
<td>{{ $matiere->date_ajout ? $matiere->date_ajout->format('d/m/Y') : '-' }}</td>
<td class="fw-semibold">{{ $matiere->nom }}</td>
<td>kg</td>
<td class="text-end">
<a href="{{ route('admin.matieres-premieres.edit',$matiere) }}" class="btn btn-sm btn-outline-secondary" title="Modifier"><i class="bi bi-pencil"></i></a>
<form action="{{ route('admin.matieres-premieres.destroy',$matiere) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette matière première ?')">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endif
</div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function(){
if($('#matieresPremieresTable').length){
$('#matieresPremieresTable').DataTable({
language:{
sEmptyTable:"Aucune donnée disponible dans le tableau",
sInfo:"Affichage de _START_ à _END_ sur _TOTAL_ éléments",
sInfoEmpty:"Affichage de 0 à 0 sur 0 élément",
sInfoFiltered:"(filtré à partir de _MAX_ éléments au total)",
sLengthMenu:"Afficher _MENU_ éléments",
sLoadingRecords:"Chargement...",
sProcessing:"Traitement...",
sSearch:"Rechercher :",
sZeroRecords:"Aucun élément correspondant trouvé",
oPaginate:{sFirst:"Premier",sLast:"Dernier",sNext:"Suivant",sPrevious:"Précédent"}
},
pageLength:10,
responsive:true,
autoWidth:false,
columnDefs:[{orderable:false,targets:3}],
order:[[0,'desc']]
});
}
});
</script>
@endpush