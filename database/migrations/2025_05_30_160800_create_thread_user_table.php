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
        Schema::create('thread_user', function (Blueprint $t) {
        $t->foreignId('thread_id')->constrained()->cascadeOnDelete();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->primary(['thread_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_user');
    }
};
