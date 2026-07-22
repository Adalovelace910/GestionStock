<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'produits';


    protected $fillable = [
        'nom',
        'description',
        'category_id',
        'quantite',
        'prix',
    ];


    public function categorie()
    {
        return $this->belongsTo(Category::class);
    }

}