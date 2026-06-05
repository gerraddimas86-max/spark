<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cft_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('cft_challenges')->onDelete('cascade');
            $table->string('answer');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            
            // Satu user cuma bisa sekali jawab per challenge (jika sudah benar, tidak bisa lagi)
            $table->unique(['user_id', 'challenge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cft_attempts');
    }
};