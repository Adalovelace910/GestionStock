<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->decimal('quantite', 10, 2)
                ->default(0)
                ->change();
        });

        Schema::table('matieres_premieres', function (Blueprint $table) {
            $table->decimal('quantite', 10, 2)
                ->default(0)
                ->change();
        });

        Schema::table('entrees', function (Blueprint $table) {
            $table->decimal('quantite', 10, 2)
                ->change();

            $table->decimal('quantite_matiere_premiere', 10, 2)
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->integer('quantite')
                ->default(0)
                ->change();
        });

        Schema::table('matieres_premieres', function (Blueprint $table) {
            $table->integer('quantite')
                ->default(0)
                ->change();
        });

        Schema::table('entrees', function (Blueprint $table) {
            $table->integer('quantite')
                ->change();

            $table->integer('quantite_matiere_premiere')
                ->nullable()
                ->change();
        });
    }
};