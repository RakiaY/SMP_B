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
        Schema::create('search_pet_sitter_slots', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers la recherche
            $table->foreignId('search_pet_sitter_id')
                  ->constrained('search_pet_sitters')
                  ->onDelete('cascade');

            // Ordre du passage (1,2,3...)
            $table->unsignedTinyInteger('slot_order')
                  ->comment('Numéro du passage dans la journée');

            // Horaires de début et de fin
            $table->time('start_time')
                  ->comment('Heure de début de la visite');
            $table->time('end_time')
                  ->comment('Heure de fin de la visite');

            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
