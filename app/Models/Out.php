<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Out extends Model
{
    protected $table = 'sorties';

    protected $fillable = [
        'produit_id',
        'user_id',
        'quantite',
        'date_sortie',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'date_sortie' => 'date',
    ];

    public function produit()
    {
        return $this->belongsTo(Product::class, 'produit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}