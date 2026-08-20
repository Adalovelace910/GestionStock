<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegleProduction extends Model
{
    protected $table = 'regles_production';

    protected $fillable = [
        'matiere_premiere_id',
        'produit_id',
        'quantite_par_kg',
    ];

    protected $casts = [
        'quantite_par_kg' => 'decimal:4',
    ];

    public function matierePremiere()
    {
        return $this->belongsTo(
            MatierePremiere::class,
            'matiere_premiere_id'
        );
    }

    public function produit()
    {
        return $this->belongsTo(
            Product::class,
            'produit_id'
        );
    }
}