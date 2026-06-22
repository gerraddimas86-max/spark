<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User; // Pastikan model Role di-import
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat data Role default terlebih dahulu
        $developerRole = Role::firstOrCreate(['name' => 'developer']);
        $mentorRole = Role::firstOrCreate(['name' => 'mentor']);
        $mahasiswaRole = Role::firstOrCreate(['name' => 'mahasiswa']);

        // 2. Masukkan Akun Developer
        User::factory()->create([
            'name' => 'Developer Spark',
            'email' => 'developer@spark.com',
            'nim' => '0000000001', // Sesuaikan atau biarkan jika opsional
            'password' => Hash::make('password'), // Password di-encrypt
            'role_id' => $developerRole->id,
            'is_active' => true,
        ]);

        // 3. Masukkan Akun Mentor
        User::factory()->create([
            'name' => 'Mentor One',
            'email' => 'mentor1@spark.com',
            'nim' => '0000000002', // Sesuaikan atau biarkan jika opsional
            'password' => Hash::make('password'), // Password di-encrypt
            'role_id' => $mentorRole->id,
            'is_active' => true,
        ]);
    }
}
