<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quest_id')->constrained('quests')->onDelete('cascade');
            $table->boolean('is_completed')->default(false);
            $table->date('completed_date')->nullable();
            $table->date('quest_date'); // Tanggal quest diberikan (untuk daily quest)
            $table->timestamps();
            
            // Satu user cuma bisa punya 1 record per quest per hari
            $table->unique(['user_id', 'quest_id', 'quest_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_quests');
    }
};