<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sortie extends Model
{

    protected $fillable = [

        'produit_id',
        'quantite',
        'date_sortie'

    ];


    protected $casts = [

        'date_sortie' => 'date',

    ];


    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

}