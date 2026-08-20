<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entrees', function (Blueprint $table) {
            $table->foreignId('production_id')
                ->nullable()
                ->after('user_id')
                ->constrained('productions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('entrees', function (Blueprint $table) {
            $table->dropForeign(['production_id']);
            $table->dropColumn('production_id');
        });
    }
};