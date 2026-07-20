<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{

    protected $table = 'parametres';


    protected $fillable = [

        'nom_entreprise',
        'adresse',
        'telephone',
        'seuil_stock_bas',
        'unite_mesure_defaut',
        'elements_par_page',

    ];

}