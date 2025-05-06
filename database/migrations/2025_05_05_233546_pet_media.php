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
       
            Schema::create('pet_medias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade'); 
                $table->string('media_patth'); //le chemin du fichier
                $table->enum('media_type', ['photo', 'video']); 
                $table->boolean('is_thumbnail')->default(false);//Image principale (true/false)
                $table->timestamp('uploaded_at')->nullable();
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
