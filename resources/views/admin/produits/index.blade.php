@extends('layouts.app')

@section('title', 'Produits')

@section('page-title', 'Gestion des produits')

@section('content')


<div class="d-flex justify-content-between align-items-center mb-4">


   <h5 class="mb-0">Produits</h5>


    @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.produits.create') }}"
       class="btn btn-primary">

        <i class="bi bi-plus-lg me-1"></i>

        Ajouter un produit

    </a>
    @endif


</div>





<div class="card shadow-sm border-0">


    <div class="card-body">



        @if($produits->isEmpty())


            <p class="text-muted mb-0">

                Aucun produit enregistré pour le moment.

            </p>



        @else



            <div class="table-responsive">


                <table id="produitsTable"
                       class="table table-bordered table-hover align-middle">



                    <thead class="table-light">


                        <tr>

                            <th>Nom</th>

                            <th>Catégorie</th>

                            <th>Description</th>

                            <th>Quantité</th>

                            <th>Prix unitaire</th>

                            <th>Valeur du stock</th>

                            @if(auth()->user()->role === 'admin')
                            <th class="text-end">
                                Actions
                            </th>
                            @endif


                        </tr>



                    </thead>





                    <tbody>


                    @foreach($produits as $produit)



                        <tr>



                            <td>

                                {{ $produit->nom }}

                            </td>





                            <td>


                                @if($produit->categorie)


                                    <span class="badge bg-primary">

                                        {{ $produit->categorie->nom }}

                                    </span>



                                @else


                                    <span class="text-muted">

                                        Aucune

                                    </span>



                                @endif


                            </td>






                            <td>


                                {{ Str::limit($produit->description,40) ?: '—' }}


                            </td>






                            <td>


                                {{ $produit->quantite }}


                            </td>






                            <td>


                                {{ number_format($produit->prix,0,',',' ') }}

                                FCFA


                            </td>







                            <td>


                                {{ number_format(
                                    $produit->quantite * $produit->prix,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                                FCFA


                            </td>







                            @if(auth()->user()->role === 'admin')
                            <td class="text-end">



                                <a href="{{ route('admin.produits.edit',$produit) }}"
                                   class="btn btn-sm btn-outline-primary">


                                    <i class="bi bi-pencil"></i>


                                </a>






                                <form action="{{ route('admin.produits.destroy',$produit) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ce produit ?')">


                                    @csrf

                                    @method('DELETE')



                                    <button class="btn btn-sm btn-outline-danger">


                                        <i class="bi bi-trash"></i>


                                    </button>



                                </form>




                            </td>
                            @endif



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


$(document).ready(function () {



    $('#produitsTable').DataTable({



        language: {


            sEmptyTable:
            "Aucune donnee disponible dans le tableau",


            sInfo:
            "Affichage de _START_ a _END_ sur _TOTAL_ entrees",


            sInfoEmpty:
            "Affichage de 0 a 0 sur 0 entree",


            sInfoFiltered:
            "(filtre a partir de _MAX_ entrees au total)",


            sLengthMenu:
            "Afficher _MENU_ elements",


            sLoadingRecords:
            "Chargement...",


            sProcessing:
            "Traitement...",


            sSearch:
            "Rechercher :",


            sZeroRecords:
            "Aucun element correspondant trouve",



            oPaginate: {


                sFirst:
                "Premier",


                sLast:
                "Dernier",


                sNext:
                "Suivant",


                sPrevious:
                "Precedent"


            }



        },



        pageLength: 10,



        columnDefs: [

            {

                orderable:false,

                targets: {{ auth()->user()->role === 'admin' ? 6 : -1 }}

            }

        ]



    });



});



</script>


@endpush