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
        // NOTE : on ne recrée pas la colonne, on la modifie :
        $table->decimal('latitude', 10, 8)->change();
        $table->decimal('longitude', 11, 8)->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_pet_sitters', function (Blueprint $table) {
            //
        });
    }
};
