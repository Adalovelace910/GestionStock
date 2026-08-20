@extends('layouts.app')
@section('title','Historique des productions')
@section('page-title','Historique des productions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
<h5 class="mb-0">Productions</h5>
<a href="{{ route('admin.production.create') }}" class="btn btn-success">
<i class="bi bi-plus-lg me-1"></i>Faire une production
</a>
</div>
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm border-0">
<div class="card-body">
<div class="table-responsive">
<table id="productionsTable" class="table table-hover align-middle mb-0">
<thead>
<tr>
<th>Date</th>
<th>Matière première</th>
<th>Quantité utilisée</th>
<th>Produits obtenus</th>
<th>Enregistrée par</th>
<th class="text-end">Actions</th>
</tr>
</thead>
<tbody>
@forelse($productions as $production)
<tr>
<td data-order="{{ $production->date_production->format('Y-m-d') }}">
{{ $production->date_production->format('d/m/Y') }}
</td>
<td>
<span class="fw-semibold">{{ $production->matierePremiere->nom ?? '—' }}</span>
</td>
<td data-order="{{ $production->quantite_matiere_premiere }}">
{{ number_format($production->quantite_matiere_premiere,2,',',' ') }} kg
</td>
<td>
@forelse($production->entrees as $entree)
<div class="mb-1"><span class="badge bg-success-subtle text-success-emphasis">{{ $entree->produit->nom ?? '—' }} ({{ number_format($entree->quantite,2,',',' ') }})</span></div>
@empty
<span class="text-muted">Aucun</span>
@endforelse
</td>
<td>{{ $production->user->name ?? '—' }}</td>
<td class="text-end">
<a href="{{ route('admin.production.edit',$production) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
<i class="bi bi-pencil"></i>
</a>
<form action="{{ route('admin.production.destroy',$production) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette production ? La matière première utilisée sera restaurée et les produits fabriqués seront retirés du stock.')">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
<i class="bi bi-trash"></i>
</button>
</form>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="text-center text-muted py-4">Aucune production enregistrée pour le moment.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</div>
</div>

@push('scripts')
<script>
$(document).ready(function(){
$('#productionsTable').DataTable({
language:{
url:'https://cdn.datatables.net/plug-ins/2.3.3/i18n/fr-FR.json'
},
pageLength:10,
lengthMenu:[[10,25,50,100,-1],[10,25,50,100,'Tous']],
order:[[0,'desc']],
columnDefs:[
{orderable:false,targets:[3,5]},
{searchable:false,targets:[5]}
],
responsive:true,
autoWidth:false
});
});
</script>
@endpush
@endsection