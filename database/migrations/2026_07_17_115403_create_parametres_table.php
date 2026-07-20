<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('nom_entreprise')->default('Family');
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->unsignedInteger('seuil_stock_bas')->default(10);
            $table->string('unite_mesure_defaut')->default('unité');
            $table->unsignedInteger('elements_par_page')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};