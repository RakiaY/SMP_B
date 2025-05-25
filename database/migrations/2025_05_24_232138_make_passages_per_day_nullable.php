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
            $table
                ->unsignedTinyInteger('passages_per_day')
                ->nullable()          // autorise NULL
                ->default(0)       // valeur par défaut NULL
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_pet_sitters', function (Blueprint $table) {
            $table
                ->unsignedTinyInteger('passages_per_day')
                ->nullable(false)     // remise en NOT NULL
                ->default(null)
                ->change();
        });
    }
};
