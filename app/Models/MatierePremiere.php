<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatierePremiere extends Model
{
    protected $table = 'matieres_premieres';

    protected $fillable = [
        'nom',
        'description',
        'quantite',
        'date_ajout',
        'prix',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'date_ajout' => 'date',
        'prix' => 'decimal:2',
    ];

    public function produits()
    {
        return $this->hasMany(
            Product::class,
            'matiere_premiere_id'
        );
    }

    public function reglesProduction()
    {
        return $this->hasMany(
            RegleProduction::class,
            'matiere_premiere_id'
        );
    }

    public function productions()
    {
        return $this->hasMany(
            Production::class,
            'matiere_premiere_id'
        );
    }
}