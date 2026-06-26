<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\PetService;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\PetFeedLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MuaralayaController extends Controller
{
    protected $petService;

    // Mapping tipe pet ke folder gambar
    protected $petTypeMap = [
        'octopus' => 'gurita',
        'ghost' => 'hantu',
        'parrot' => 'burung-beo',
        'shark' => 'hiu',
        'pufferfish' => 'ikan-buntal',
        'crab' => 'kepiting',
        'seahorse' => 'kuda-laut',
        'turtle' => 'kura-kura',
    ];

    // 8 tipe pet untuk 8 kelompok
    protected $petTypes = [
        'octopus',
        'ghost', 
        'parrot',
        'shark',
        'pufferfish',
        'crab',
        'seahorse',
        'turtle',
    ];

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    /**
     * Menampilkan halaman pulau Muaralaya (Pet)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data pet berdasarkan group user
        $pet = $this->getUserPet($user);
        
        // Ambil riwayat makan (5 terakhir)
        $feedHistory = PetFeedLog::where('pet_id', $pet->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Ambil food points dari user
        $foodPoints = $user->food_points ?? 0;
        
        // Data pet untuk view (dikirim langsung)
        $petData = [
            'id' => $pet->id,
            'name' => $pet->name,
            'type' => $pet->type,
            'level' => $pet->level,
            'experience' => $pet->experience,
            'exp_needed' => $this->getExpNeeded($pet->level),
            'exp_progress' => $this->getExpProgress($pet),
            'stage' => $pet->stage,
            'stage_name' => $pet->stage_name,
            'stage_badge_color' => $pet->stage_badge_color,
            'icon' => $pet->icon,
            'stage_icon' => $pet->stage_icon,
            'image_url' => $pet->image,
            'food_points' => $user->food_points ?? 0,
        ];
        
        // Data riwayat untuk view
        $historyData = $feedHistory->map(function ($item) {
            return [
                'date' => $item->created_at->diffForHumans(),
                'food_amount' => $item->food_amount,
                'user_name' => $item->user ? $item->user->name : 'Unknown',
            ];
        });
        
        // Nama grup untuk ditampilkan
        $groupName = $user->group ? $user->group->name : null;
        
        return view('student.islands.muaralaya.index', compact('pet', 'petData', 'feedHistory', 'historyData', 'foodPoints', 'groupName'));
    }
    
    /**
     * Memberi makan pet (via AJAX)
     */
    public function feed(Request $request)
    {
        $user = Auth::user();
        $pet = $this->getUserPet($user);
        
        // Gunakan PetService untuk memberi makan
        $result = $this->petService->feedPet($user, $pet, 10);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }
        
        // Refresh pet untuk data terbaru
        $pet->refresh();
        
        // Data untuk response
        $data = [
            'success' => true,
            'message' => $result['message'],
            'pet' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'level' => $pet->level,
                'experience' => $pet->experience,
                'exp_needed' => $this->getExpNeeded($pet->level),
                'exp_progress' => $this->getExpProgress($pet),
                'stage' => $pet->stage,
                'stage_name' => $pet->stage_name,
                'icon' => $pet->icon,
                'stage_icon' => $pet->stage_icon,
                'type' => $pet->type,
                'image_url' => $pet->image,
            ],
            'food_points' => $user->food_points,
            'level_up' => $result['leveled_up'] ?? false,
            'stage_changed' => $result['stage_changed'] ?? false,
            'new_stage' => $result['new_stage'] ?? null,
            'old_stage' => $result['old_stage'] ?? null,
            'group_health' => $result['group_health'] ?? 100,
        ];
        
        return response()->json($data);
    }
    
    /**
     * Mendapatkan pet user
     * Jika user di group, ambil pet group, jika tidak buat baru
     */
    private function getUserPet($user)
    {
        if ($user->group_id) {
            $pet = Pet::where('group_id', $user->group_id)->first();
            if ($pet) {
                return $pet;
            }
            return $this->createPetForGroup($user->group_id);
        }
        return $this->createPetForUser($user);
    }
    
    /**
     * Membuat pet untuk group dengan tipe unik berdasarkan ID group
     */
    private function createPetForGroup($groupId)
    {
        $group = \App\Models\Group::find($groupId);
        $groupName = $group ? $group->name : 'Group';
        
        // Pilih tipe pet berdasarkan group_id (1-8)
        // Group 1 = octopus, Group 2 = ghost, dst.
        $typeIndex = ($groupId - 1) % count($this->petTypes);
        $type = $this->petTypes[$typeIndex];
        
        return Pet::create([
            'name' => 'Pet ' . $groupName,
            'group_id' => $groupId,
            'type' => $type,
            'level' => 0,
            'experience' => 0,
            'stage' => 'egg',
            'image_url' => null,
        ]);
    }
    
    /**
     * Membuat pet untuk user (jika tidak punya group)
     */
    private function createPetForUser($user)
    {
        // Random tipe pet untuk user tanpa group
        $randomType = $this->petTypes[array_rand($this->petTypes)];
        
        return Pet::create([
            'name' => 'Pet ' . $user->name,
            'group_id' => null,
            'type' => $randomType,
            'level' => 0,
            'experience' => 0,
            'stage' => 'egg',
            'image_url' => null,
        ]);
    }
    
    /**
     * Mendapatkan EXP yang dibutuhkan untuk level tertentu
     */
    private function getExpNeeded($level)
    {
        return ($level + 1) * 100;
    }
    
    /**
     * Mendapatkan progress EXP dalam persentase
     */
    private function getExpProgress($pet)
    {
        $expNeeded = $this->getExpNeeded($pet->level);
        return round(($pet->experience / $expNeeded) * 100, 1);
    }

    /**
     * Mendapatkan mapping tipe pet ke folder
     */
    private function getPetFolder($type)
    {
        return $this->petTypeMap[$type] ?? 'gurita';
    }

    // ============================================================
    //  API METHODS (untuk frontend AJAX)
    // ============================================================

    /**
     * API: Mendapatkan data pet terbaru
     */
    public function getPetData()
    {
        $user = Auth::user();
        $pet = $this->getUserPet($user);
        
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pet->id,
                'name' => $pet->name,
                'type' => $pet->type,
                'level' => $pet->level,
                'experience' => $pet->experience,
                'exp_needed' => $this->getExpNeeded($pet->level),
                'exp_progress' => $this->getExpProgress($pet),
                'stage' => $pet->stage,
                'stage_name' => $pet->stage_name,
                'stage_badge_color' => $pet->stage_badge_color,
                'icon' => $pet->icon,
                'stage_icon' => $pet->stage_icon,
                'image_url' => $pet->image,
                'food_points' => $user->food_points ?? 0,
            ]
        ]);
    }

    /**
     * API: Mendapatkan riwayat makan pet
     */
    public function getFeedHistory()
    {
        $user = Auth::user();
        $pet = $this->getUserPet($user);
        
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet tidak ditemukan'
            ], 404);
        }
        
        $history = PetFeedLog::where('pet_id', $pet->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->created_at->diffForHumans(),
                    'food_amount' => $item->food_amount,
                    'user_name' => $item->user ? $item->user->name : 'Unknown',
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * API: Mendapatkan semua tipe pet yang tersedia
     */
    public function getPetTypes()
    {
        $types = [
            ['value' => 'octopus', 'label' => '🐙 Gurita'],
            ['value' => 'ghost', 'label' => '👻 Hantu'],
            ['value' => 'parrot', 'label' => '🦜 Burung Beo'],
            ['value' => 'shark', 'label' => '🦈 Hiu'],
            ['value' => 'pufferfish', 'label' => '🐡 Ikan Buntal'],
            ['value' => 'crab', 'label' => '🦀 Kepiting'],
            ['value' => 'seahorse', 'label' => '🐴 Kuda Laut'],
            ['value' => 'turtle', 'label' => '🐢 Kura-kura'],
        ];
        
        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    /**
     * API: Ganti tipe pet
     */
    public function changePetType(Request $request)
    {
        $request->validate([
            'type' => 'required|in:octopus,ghost,parrot,shark,pufferfish,crab,seahorse,turtle',
        ]);
        
        $user = Auth::user();
        $pet = $this->getUserPet($user);
        
        if (!$pet) {
            return response()->json([
                'success' => false,
                'message' => 'Pet tidak ditemukan'
            ], 404);
        }
        
        $pet->type = $request->type;
        $pet->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Tipe pet berhasil diganti!',
            'data' => [
                'type' => $pet->type,
                'icon' => $pet->icon,
                'image_url' => $pet->image,
            ]
        ]);
    }
}