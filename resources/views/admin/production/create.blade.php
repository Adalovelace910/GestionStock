@extends('layouts.app')

@section('title', 'Faire une production')

@section('page-title', 'Faire une production')

@section('content')

@php
    $routePrefix =
        auth()->user()->role === 'admin'
            ? 'admin'
            : 'magasinier';
@endphp


<div class="row">

    <div >

       <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">Faire une production</h5>

            <a
                href="{{ route(
                    $routePrefix . '.production.index'
                ) }}"
                class="btn btn-outline-secondary">

                <i class="bi bi-list me-1"></i>

                Liste

            </a>
       </div>


        <div class="card w-50 shadow-sm border-0">

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

                <form
                    action="{{ route(
                        $routePrefix . '.production.store'
                    ) }}"
                    method="POST">

                    @csrf


                    <div class="mb-3">

                        <label
                            for="matiere_premiere_id"
                            class="form-label">

                            Matière première

                        </label>


                        <select
                            name="matiere_premiere_id"
                            id="matiere_premiere_id"
                            class="form-select"
                            required>

                            <option value="">

                                -- Choisir une matière première --

                            </option>


                            @foreach(
                                $matieresPremieres
                                as $matierePremiere
                            )

                                <option
                                    value="{{ $matierePremiere->id }}"
                                    data-stock="{{ $matierePremiere->quantite }}"
                                    @selected(
                                        old(
                                            'matiere_premiere_id',
                                            $matierePremiereSelectionnee
                                        )
                                        ==
                                        $matierePremiere->id
                                    )>

                                    {{ $matierePremiere->nom }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div
                        id="stock-info"
                        class="alert alert-light border d-none mb-3">
                    </div>


                    <div class="mb-3">

                        <label
                            for="quantite_matiere_premiere"
                            class="form-label">

                            Quantité utilisée (kg)

                        </label>


                        <input
                            type="number"
                            name="quantite_matiere_premiere"
                            id="quantite_matiere_premiere"
                            min="1"
                            step="1"
                            value="{{ old(
                                'quantite_matiere_premiere'
                            ) }}"
                            class="form-control"
                            placeholder="Ex : 5"
                            required>

                    </div>


                    <div class="mb-4">

                        <label
                            for="date_production"
                            class="form-label">

                            Date de production

                        </label>


                        <input
                            type="date"
                            name="date_production"
                            id="date_production"
                            value="{{ old(
                                'date_production',
                                now()->format('Y-m-d')
                            ) }}"
                            class="form-control"
                            required>

                    </div>


                    <div class="card border mb-4">

                        <div class="card-body">

                            <h6 class="fw-bold mb-3">

                                Résultat prévu

                            </h6>


                            <div
                                id="aucune-regle"
                                class="text-muted">

                                Choisissez une matière première
                                pour voir les produits obtenus.

                            </div>


                            <div
                                id="resultats-production">
                            </div>

                        </div>

                    </div>


                    <button
    type="submit"
    class="btn btn-primary">

    Faire la production

</button>


                    <a
                        href="{{ route(
                            $routePrefix
                            . '.entrees.index'
                        ) }}"
                        class="btn btn-outline-secondary ms-2">

                        Annuler

                    </a>

                </form>

            </div>

        </div>

    </div>

</div>


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const selectMatiere =
            document.getElementById(
                'matiere_premiere_id'
            );

        const inputQuantite =
            document.getElementById(
                'quantite_matiere_premiere'
            );

        const stockInfo =
            document.getElementById(
                'stock-info'
            );

        const resultats =
            document.getElementById(
                'resultats-production'
            );

        const aucuneRegle =
            document.getElementById(
                'aucune-regle'
            );


        const regles =
            @json($regles);


        function formatNumber(value)
        {
            return Number(value).toLocaleString(
                'fr-FR',
                {
                    maximumFractionDigits: 0
                }
            );
        }


        function afficherResultat()
        {
            const option =
                selectMatiere.options[
                    selectMatiere.selectedIndex
                ];


            const matiereId =
                selectMatiere.value;


            const quantite =
                parseFloat(
                    inputQuantite.value
                ) || 0;


            resultats.innerHTML = '';

            stockInfo.classList.add(
                'd-none'
            );

            aucuneRegle.classList.remove(
                'd-none'
            );


            if (!matiereId) {

                aucuneRegle.textContent =
                    'Choisissez une matière première pour voir les produits obtenus.';

                return;
            }


            const stock =
                parseFloat(
                    option.dataset.stock
                ) || 0;


            stockInfo.textContent =
                'Stock disponible : '
                + formatNumber(stock)
                + ' kg';


            stockInfo.classList.remove(
                'd-none'
            );


            const lignes =
                regles[matiereId] || [];


            if (lignes.length === 0) {

                aucuneRegle.textContent =
                    'Aucune règle de production n’est configurée pour cette matière première. Allez dans Paramètres.';

                return;
            }


            aucuneRegle.classList.add(
                'd-none'
            );


            lignes.forEach(
                function (regle) {

                    const production =
                        Math.round(
                            quantite
                            *
                            Number(
                                regle.quantite_par_kg
                            )
                        );


                    const ligne =
                        document.createElement(
                            'div'
                        );


                    ligne.className =
                        'd-flex justify-content-between align-items-center border-bottom py-2';


                    const detailTexte = production > 0
                        ? `<strong class="text-success">${formatNumber(production)} sac(s)</strong>`
                        : `<span class="text-warning small">Quantité insuffisante pour fabriquer 1 sac</span>`;


                    ligne.innerHTML = `
                        <span>
                            ${regle.produit_nom}
                        </span>
                        ${detailTexte}
                    `;


                    resultats.appendChild(
                        ligne
                    );

                }
            );
        }


        selectMatiere.addEventListener(
            'change',
            afficherResultat
        );


        inputQuantite.addEventListener(
            'input',
            afficherResultat
        );


        afficherResultat();

    }
);

</script>

@endpush

@endsection