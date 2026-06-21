<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Group;
use App\Models\Pet;
use App\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Data 8 kelompok
        $groups = [
            ['name' => 'Bajak Laut Merah', 'code' => 'BLM01'],
            ['name' => 'Bajak Laut Hitam', 'code' => 'BLH01'],
            ['name' => 'Bajak Laut Biru', 'code' => 'BLB01'],
            ['name' => 'Bajak Laut Hijau', 'code' => 'BLG01'],
            ['name' => 'Bajak Laut Kuning', 'code' => 'BLK01'],
            ['name' => 'Bajak Laut Ungu', 'code' => 'BLU01'],
            ['name' => 'Bajak Laut Oranye', 'code' => 'BLO01'],
            ['name' => 'Bajak Laut Putih', 'code' => 'BLP01'],
        ];
        
        // Data 8 pet dengan tipe berbeda
        $pets = [
            ['type' => 'ghost', 'name' => 'Phantom', 'stage' => 'egg'],
            ['type' => 'parrot', 'name' => 'Captain', 'stage' => 'egg'],
            ['type' => 'shark', 'name' => 'Finley', 'stage' => 'egg'],
            ['type' => 'octopus', 'name' => 'Octavius', 'stage' => 'egg'],
            ['type' => 'dragon', 'name' => 'Draco', 'stage' => 'egg'],
            ['type' => 'phoenix', 'name' => 'Ember', 'stage' => 'egg'],
            ['type' => 'turtle', 'name' => 'Shelly', 'stage' => 'egg'],
            ['type' => 'whale', 'name' => 'Wally', 'stage' => 'egg'],
        ];
        
        foreach ($groups as $index => $groupData) {
            // Buat kelompok
            $group = Group::updateOrCreate(
                ['code' => $groupData['code']],
                [
                    'name' => $groupData['name'],
                    'pet_health' => 0,
                ]
            );
            
            // Buat pet untuk kelompok
            Pet::updateOrCreate(
                ['group_id' => $group->id],
                [
                    'name' => $pets[$index]['name'],
                    'type' => $pets[$index]['type'],
                    'level' => 1,
                    'experience' => 0,
                    'stage' => $pets[$index]['stage'],
                ]
            );
        }
    }

    public function down(): void
    {
        // Hapus semua kelompok (pet akan ikut terhapus karena cascade)
        Group::whereIn('code', [
            'BLM01', 'BLH01', 'BLB01', 'BLG01', 'BLK01', 'BLU01', 'BLO01', 'BLP01'
        ])->delete();
    }
};