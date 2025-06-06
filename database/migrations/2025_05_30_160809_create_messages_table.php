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
        Schema::create('messages', function (Blueprint $t) {
        $t->id();
        $t->foreignId('thread_id')->constrained()->cascadeOnDelete();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->text('body');
        $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
