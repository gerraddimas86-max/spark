<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CftChallenge;

class CftChallengeSeeder extends Seeder
{
    public function run(): void
    {
        CftChallenge::insert([
            [
                'title' => 'Base64 Basics',
                'description' => 'Decode teks ini: U1BBUksgSXMgQXdlc29tZQ==',
                'flag' => 'SPARK Is Awesome',
                'food_reward' => 15,
                'points' => 100,
                'is_active' => true,
            ],
            [
                'title' => 'Inspect Me',
                'description' => 'Buka Inspect Element (F12), cari flag di console atau elements. Flag ada di komentar HTML.',
                'flag' => 'SPARK{inspect_elite}',
                'food_reward' => 20,
                'points' => 150,
                'is_active' => true,
            ],
            [
                'title' => 'Hidden Link',
                'description' => 'Ada tautan tersembunyi di halaman ini. Temukan! (petunjuk: cek atribut style display:none)',
                'flag' => 'SPARK{hidden_treasure}',
                'food_reward' => 25,
                'points' => 200,
                'is_active' => true,
            ],
            [
                'title' => 'Caesar Cipher',
                'description' => 'Dekripsi pesan berikut dengan shift 3: "VSDQN LV DZHVRPH"',
                'flag' => 'SPARK IS AWESOME',
                'food_reward' => 20,
                'points' => 150,
                'is_active' => true,
            ],
            [
                'title' => 'Simple Math',
                'description' => 'Berapa hasil dari 15 * 8 + 22? Masukkan angka saja.',
                'flag' => '142',
                'food_reward' => 10,
                'points' => 50,
                'is_active' => true,
            ],
        ]);
    }
}