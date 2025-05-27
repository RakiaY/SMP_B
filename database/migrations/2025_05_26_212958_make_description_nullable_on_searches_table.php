<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('search_pet_sitters', function (Blueprint $table) {
            // rend la colonne nullable
            $table->text('description')->nullable()->change();
        });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('searches', function (Blueprint $table) {
             Schema::table('searches', function (Blueprint $table) {
            // revient en arrière : non nullable, sans default
            $table->text('description')->nullable(false)->change();
        });
        });
    }
};
