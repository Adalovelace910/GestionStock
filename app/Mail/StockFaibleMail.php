<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StockFaibleMail extends Mailable
{
    use Queueable, SerializesModels;

    public Product $produit;

    public function __construct(Product $produit)
    {
        $this->produit = $produit;
    }

    public function build()
    {
        return $this->subject('⚠ Alerte stock faible : ' . $this->produit->nom)
            ->view('admin.emails.stock_faible');
    }
}