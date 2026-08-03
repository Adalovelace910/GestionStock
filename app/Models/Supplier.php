<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'fournisseurs';

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'adresse'
    ];
}