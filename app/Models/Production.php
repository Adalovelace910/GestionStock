<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'matiere_premiere_id',
        'quantite_matiere_premiere',
        'date_production',
        'user_id',
    ];

    protected $casts = [
        'quantite_matiere_premiere' => 'decimal:2',
        'date_production' => 'date',
    ];

    public function matierePremiere()
    {
        return $this->belongsTo(
            MatierePremiere::class,
            'matiere_premiere_id'
        );
    }

    public function entrees()
    {
        return $this->hasMany(
            In::class,
            'production_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}