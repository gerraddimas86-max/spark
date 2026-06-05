<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ganti email menjadi nullable karena mahasiswa pakai NIM
            $table->string('email')->nullable()->change();
            
            // Tambah kolom NIM (untuk login mahasiswa)
            $table->string('nim')->unique()->nullable()->after('id');
            
            // Tambah role_id
            $table->foreignId('role_id')->after('email')->constrained('roles');
            
            // Tambah group_id (mahasiswa punya 1 kelompok)
            $table->foreignId('group_id')->nullable()->after('role_id')->constrained('groups');
            
            // Food points untuk memberi makan pet
            $table->integer('food_points')->default(0)->after('group_id');
            
            // Status aktif (bisa dinonaktifkan mentor)
            $table->boolean('is_active')->default(true)->after('food_points');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropColumn(['nim', 'role_id', 'group_id', 'food_points', 'is_active']);
        });
    }
};