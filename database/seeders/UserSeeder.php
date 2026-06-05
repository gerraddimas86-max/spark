<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Developer (login pakai email)
        User::create([
            'name' => 'Developer SPARK',
            'email' => 'developer@spark.com',
            'nim' => null,
            'password' => Hash::make('password'),
            'role_id' => 1, // developer
            'group_id' => null,
            'food_points' => 0,
            'is_active' => true,
        ]);
        
        // Contoh akun Mentor (nanti bisa ditambah lewat dashboard developer)
        User::create([
            'name' => 'Mentor 1',
            'email' => 'mentor1@spark.com',
            'nim' => null,
            'password' => Hash::make('password'),
            'role_id' => 2, // mentor
            'group_id' => null,
            'food_points' => 0,
            'is_active' => true,
        ]);
    }
}