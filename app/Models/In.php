<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class In extends Model
{
    protected $table = 'entrees';

    protected $fillable = [
        'produit_id',
        'user_id',
        'production_id',
        'matiere_premiere_id',
        'quantite_matiere_premiere',
        'rendement',
        'quantite',
        'date_entree',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'quantite_matiere_premiere' => 'integer',
        'rendement' => 'decimal:2',
        'date_entree' => 'date',
    ];

    public function produit()
    {
        return $this->belongsTo(
            Product::class,
            'produit_id'
        );
    }

    public function matierePremiere()
    {
        return $this->belongsTo(
            MatierePremiere::class,
            'matiere_premiere_id'
        );
    }

    public function production()
    {
        return $this->belongsTo(
            Production::class,
            'production_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}