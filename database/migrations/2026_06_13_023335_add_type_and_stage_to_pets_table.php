<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            // Tambah kolom type jika belum ada
            if (!Schema::hasColumn('pets', 'type')) {
                $table->string('type')->default('ghost')->after('name');
            }
            
            // Tambah kolom stage jika belum ada
            if (!Schema::hasColumn('pets', 'stage')) {
                $table->string('stage')->default('egg')->after('level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['type', 'stage']);
        });
    }
};