<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('entrees', function (Blueprint $table) {

            $table->id();


            $table->unsignedBigInteger('produit_id');


            $table->integer('quantite');


            $table->date('date_entree');


            $table->timestamps();



            $table->foreign('produit_id')
                ->references('id')
                ->on('produits')
                ->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('entrees');
    }
};
