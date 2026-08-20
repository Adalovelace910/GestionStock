@extends('layouts.app')

@section('title', 'Produire')

@section('content')

<div class="mb-4">
    <h5 class="mb-0">Produire à partir de "{{ $matierePremiere->nom }}"</h5>
</div>

<div class="row">
<div class="col-md-6">
<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="{{ route('admin.production.produire.store', $matierePremiere) }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Produit obtenu</label>
                    <input type="text" class="form-control" readonly
                           value="{{ $matierePremiere->produit->nom ?? '—' }}"value="{{ $matierePremiere->produits->first()->nom ?? '—' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Stock matière première</label>
                    <input type="text" id="stock-mp" class="form-control" readonly
                           value="{{ number_format($matierePremiere->quantite, 2, ',', ' ') }} kg (~{{ number_format($matierePremiere->stock_en_sacs, 2, ',', ' ') }} sac(s))">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Rendement</label>
                <input type="text" class="form-control" readonly
                       value="{{ number_format($matierePremiere->rendement, 2, ',', ' ') }} %">
            </div>

            <div class="row mb-3">
                <div class="col-md-5">
                    <label class="form-label">Unité de saisie</label>
                    <select name="unite" id="unite" class="form-select @error('unite') is-invalid @enderror">
                        <option value="kg" @selected(old('unite') == 'kg')>Kilogrammes (kg)</option>
                        <option value="sac" @selected(old('unite') == 'sac')>Sacs ({{ number_format($poidsSac, 0) }} kg/sac)</option>
                    </select>
                    @error('unite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-7">
                    <label class="form-label">Quantité de matière première utilisée</label>
                    <input type="number" step="0.01" min="0.01" name="quantite" id="quantite"
                           value="{{ old('quantite') }}"
                           class="form-control @error('quantite') is-invalid @enderror">
                    @error('quantite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="alert alert-light border small" id="apercu-production">
                Saisissez une quantité pour voir la production estimée.
            </div>

            <div class="mb-4">
                <label class="form-label">Date d'entrée</label>
                <input type="date" name="date_entree" value="{{ old('date_entree', date('Y-m-d')) }}"
                       class="form-control @error('date_entree') is-invalid @enderror">
                @error('date_entree')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-gear-wide-connected me-1"></i> Enregistrer la production
            </button>
            <a href="{{ route('admin.production.index') }}" class="btn btn-outline-secondary">Annuler</a>

        </form>

    </div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const poidsSac = {{ (float) $poidsSac }};
    const rendement = {{ $matierePremiere->rendement !== null ? (float) $matierePremiere->rendement : 'null' }};
    const stockMpKg = {{ (float) $matierePremiere->quantite }};

    const unite = document.getElementById('unite');
    const quantite = document.getElementById('quantite');
    const apercu = document.getElementById('apercu-production');

    function mettreAJourApercu() {
        const qte = parseFloat(quantite.value);

        if (!qte || qte <= 0 || rendement === null) {
            apercu.textContent = 'Saisissez une quantité pour voir la production estimée.';
            apercu.classList.remove('alert-danger', 'alert-success');
            apercu.classList.add('alert-light');
            return;
        }

        const qteKg = unite.value === 'sac' ? qte * poidsSac : qte;

        if (qteKg > stockMpKg) {
            apercu.textContent = 'Stock insuffisant : il ne reste que ' + stockMpKg.toLocaleString('fr-FR') + ' kg disponible.';
            apercu.classList.remove('alert-light', 'alert-success');
            apercu.classList.add('alert-danger');
            return;
        }

        const kgProduits = qteKg * (rendement / 100);
        const sacsProduits = Math.round(kgProduits / poidsSac);

        apercu.textContent = qteKg.toLocaleString('fr-FR') + ' kg utilisés → environ ' +
            sacsProduits + ' sac(s) de produit fini seront ajoutés au stock.';
        apercu.classList.remove('alert-light', 'alert-danger');
        apercu.classList.add('alert-success');
    }

    unite.addEventListener('change', mettreAJourApercu);
    quantite.addEventListener('input', mettreAJourApercu);

    mettreAJourApercu();

});
</script>

@endsection