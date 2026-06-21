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
            'stage_name' => $this->petService->getStageName($pet->stage),
            'stage_badge_color' => $this->petService->getStageBadgeColor($pet->stage),
            'emoji' => $pet->emoji,
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
        
        return view('student.islands.muaralaya.index', compact('pet', 'petData', 'feedHistory', 'historyData', 'foodPoints'));
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
                'stage_name' => $this->petService->getStageName($pet->stage),
                'emoji' => $pet->emoji,
                'type' => $pet->type,
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
    
    private function createPetForGroup($groupId)
    {
        $group = \App\Models\Group::find($groupId);
        $groupName = $group ? $group->name : 'Group';
        
        return Pet::create([
            'name' => 'Pet ' . $groupName,
            'group_id' => $groupId,
            'type' => 'octopus',
            'level' => 0,
            'experience' => 0,
            'stage' => 'egg',
            'image_url' => null,
        ]);
    }
    
    private function createPetForUser($user)
    {
        return Pet::create([
            'name' => 'Pet ' . $user->name,
            'group_id' => null,
            'type' => 'octopus',
            'level' => 0,
            'experience' => 0,
            'stage' => 'egg',
            'image_url' => null,
        ]);
    }
    
    private function getExpNeeded($level)
    {
        return ($level + 1) * 100;
    }
    
    private function getExpProgress($pet)
    {
        $expNeeded = $this->getExpNeeded($pet->level);
        return round(($pet->experience / $expNeeded) * 100, 1);
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
                'stage_name' => $this->petService->getStageName($pet->stage),
                'stage_badge_color' => $this->petService->getStageBadgeColor($pet->stage),
                'emoji' => $pet->emoji,
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
            ['value' => 'parrot', 'label' => '🦜 Burung'],
            ['value' => 'shark', 'label' => '🦈 Hiu'],
            ['value' => 'dragon', 'label' => '🐉 Naga'],
            ['value' => 'phoenix', 'label' => '🦅 Phoenix'],
            ['value' => 'turtle', 'label' => '🐢 Kura-kura'],
            ['value' => 'whale', 'label' => '🐋 Paus'],
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
            'type' => 'required|in:octopus,ghost,parrot,shark,dragon,phoenix,turtle,whale',
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
                'emoji' => $pet->emoji,
            ]
        ]);
    }
}