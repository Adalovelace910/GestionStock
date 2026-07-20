<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{

    protected $fillable = [

        'produit_id',
        'quantite',
        'date_entree'

    ];


    protected $casts = [

        'date_entree' => 'date',

    ];


    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

}