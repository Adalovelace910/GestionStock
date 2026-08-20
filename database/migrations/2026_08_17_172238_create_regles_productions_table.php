<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regles_production', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matiere_premiere_id')
                ->constrained('matieres_premieres')
                ->cascadeOnDelete();

            $table->foreignId('produit_id')
                ->constrained('produits')
                ->cascadeOnDelete();

            /*
             * Nombre de sacs obtenus pour 1 kg
             * de matière première.
             */
            $table->decimal('quantite_par_kg', 10, 4)
                ->default(0);

            $table->timestamps();

            $table->unique([
                'matiere_premiere_id',
                'produit_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regles_production');
    }
};