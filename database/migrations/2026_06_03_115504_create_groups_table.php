<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kelompok, misal: "Bajak Laut Merah"
            $table->string('code')->unique(); // Kode unik kelompok, misal: "BLM01"
            $table->integer('pet_health')->default(0); // Progress pet kelompok (0-100)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};