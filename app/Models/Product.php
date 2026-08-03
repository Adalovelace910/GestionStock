<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockFaibleMail;

class Product extends Model
{

    protected $table = 'produits';


    protected $fillable = [
        'nom',
        'description',
        'categorie_id',
        'quantite',
        'prix',
    ];


    public function categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }


    public function verifierSeuilStock(int $ancienneQuantite): void
    {
        $seuil = Setting::first()->seuil_stock_bas ?? 10;

        if ($this->quantite <= $seuil && $ancienneQuantite > $seuil) {

            $adminsEmails = User::where('role', 'admin')->pluck('email');

            foreach ($adminsEmails as $email) {
                Mail::to($email)->send(new StockFaibleMail($this));
            }

            Notification::create([
                'title' => 'Stock faible',
                'message' => "Le produit \"{$this->nom}\" a atteint le seuil minimum de stock ({$this->quantite} restant(s)).",
                'icon' => 'bi-exclamation-triangle',
            ]);
        }
    }

}