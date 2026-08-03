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
        'prix',
    ];
}