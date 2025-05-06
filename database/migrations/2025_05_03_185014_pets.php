<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function PHPSTORM_META\type;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_owner_id')->constrained('users')->onDelete('cascade'); 
            $table->string('type');
            $table->string('breed');
            $table->string('name');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('color')->nullable();
            $table->float('weight')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_vaccinated')->default(false);
            $table->boolean('has_contagious_diseases')->default(false);
            $table->boolean('has_medical_file')->default(false);
            $table->boolean('is_critical_condition')->default(false);//s’il a un état de santé critique
            $table->timestamps();


            //s’il est vacciné ou pas,
            






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
