<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quest;

class QuestSeeder extends Seeder
{
    public function run(): void
    {
        Quest::insert([
            [
                'title' => 'Login Harian',
                'description' => 'Login ke SPARK hari ini',
                'type' => 'login',
                'food_reward' => 5,
                'is_daily' => true,
            ],
            [
                'title' => 'Selesaikan 1 CFT',
                'description' => 'Kerjakan minimal 1 tantangan CFT dengan benar',
                'type' => 'cft',
                'food_reward' => 10,
                'is_daily' => true,
            ],
            [
                'title' => 'Beri Makan Pet',
                'description' => 'Beri makan pet kelompokmu',
                'type' => 'feed_pet',
                'food_reward' => 3,
                'is_daily' => true,
            ],
            [
                'title' => 'Baca Pengumuman',
                'description' => 'Baca pengumuman terbaru dari mentor',
                'type' => 'read_announcement',
                'food_reward' => 2,
                'is_daily' => true,
            ],
        ]);
    }
}