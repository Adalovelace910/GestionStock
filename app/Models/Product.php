<?php

namespace App\Models;

use App\Mail\StockFaibleMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Product extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'description',
        'categorie_id',
        'matiere_premiere_id',
        'quantite',
        'prix',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix' => 'decimal:2',
    ];

    public function categorie()
    {
        return $this->belongsTo(
            Category::class,
            'categorie_id'
        );
    }

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
            'produit_id'
        );
    }

    public function reglesProduction()
    {
        return $this->hasMany(
            RegleProduction::class,
            'produit_id'
        );
    }

    public function verifierSeuilStock(
        float $ancienneQuantite
    ): void {
        $parametre = Setting::first();

        $seuil = (float) (
            $parametre->seuil_stock_bas ?? 10
        );

        if (
            (float) $this->quantite <= $seuil
            &&
            $ancienneQuantite > $seuil
        ) {
            $emailsAlertes =
                $parametre
                    ? $parametre->listeEmailsAlertes()
                    : [];

            if (empty($emailsAlertes)) {
                $emailsAlertes =
                    User::where(
                        'role',
                        'admin'
                    )->pluck('email')->all();
            }

            foreach ($emailsAlertes as $email) {
                Mail::to($email)
                    ->send(
                        new StockFaibleMail($this)
                    );
            }

            Notification::create([
                'title' => 'Stock faible',
                'message' =>
                    "Le produit \"{$this->nom}\" "
                    . "a atteint le seuil minimum "
                    . "de stock ({$this->quantite} restant(s)).",
                'icon' =>
                    'bi-exclamation-triangle',
            ]);
        }
    }
}