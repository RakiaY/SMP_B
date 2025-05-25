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
             $table->unsignedTinyInteger('passages_per_day')
                  ->after('end_date')
                  ->comment('Nombre de visites journalières chez le propriétaire');
            $table->dropColumn('care_duration');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_pet_sitters', function (Blueprint $table) {
            $table->string('care_duration');

            // supprimer passages_per_day
            $table->dropColumn('passages_per_day');
        });
    }
};
