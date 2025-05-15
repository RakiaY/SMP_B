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
        Schema::create('search_pet_sitters' , function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); //ownerid
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->string('adresse');
            $table->decimal('latitude'); 
            $table->decimal('longitude');
            $table->text('description');
            $table->string('care_type'); // 'chez_proprietaire' or 'en_chenil'
            $table->string('care_duration');
            $table->date('start_date'); 
            $table->date('end_date'); 
            $table->text('expected_services'); //( marche;nourrissag; toilettage)
            $table->decimal ('remunerationMin');
            $table->decimal ('remunerationMax');
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
