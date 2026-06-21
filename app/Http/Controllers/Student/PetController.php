<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\PetService;
use App\Services\QuestService;
use App\Models\UserQuest;
use App\Models\PetFeedLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    protected $petService;
    protected $questService;
    
    public function __construct(PetService $petService, QuestService $questService)
    {
        $this->petService = $petService;
        $this->questService = $questService;
    }
    
    /**
     * Menampilkan halaman Pulau Pet (info pet, statistik, riwayat makan)
     */
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;
        
        // Jika mahasiswa belum memiliki kelompok
        if (!$group) {
            return view('student.islands.pet', [
                'user' => $user,
                'group' => null,
                'pet' => null,
                'foodPoints' => 0,
                'feedHistory' => collect(),
                'error' => 'Anda belum memiliki kelompok. Hubungi mentor Anda.'
            ]);
        }
        
        $pet = $group->pet;
        
        if (!$pet) {
            return view('student.islands.pet', [
                'user' => $user,
                'group' => $group,
                'pet' => null,
                'foodPoints' => 0,
                'feedHistory' => collect(),
                'error' => 'Pet belum tersedia untuk kelompok ini.'
            ]);
        }
        
        // Hitung food points user (dari quest yang sudah selesai)
        $foodPoints = 0;
        try {
            $columns = DB::getSchemaBuilder()->getColumnListing('user_quests');
            if (in_array('reward_food_points', $columns)) {
                $foodPoints = UserQuest::where('user_id', $user->id)
                    ->where('completed', true)
                    ->sum('reward_food_points');
            } elseif (in_array('reward_points', $columns)) {
                $foodPoints = UserQuest::where('user_id', $user->id)
                    ->where('completed', true)
                    ->sum('reward_points');
            } else {
                $foodPoints = UserQuest::where('user_id', $user->id)
                    ->where('completed', true)
                    ->count() * 10;
            }
        } catch (\Exception $e) {
            $foodPoints = 0;
        }
        
        // 🔥 PERBAIKAN: Ambil riwayat memberi makan (10 terakhir) - pakai 'user_id'
        $feedHistory = PetFeedLog::where('pet_id', $pet->id)
            ->where('user_id', $user->id)  // ← benar, sesuai model
            ->latest()
            ->take(10)
            ->get();
        
        // Hitung progress level
        $currentLevel = $pet->level ?? 1;
        $currentExp = $pet->experience_points ?? 0;
        $expNeeded = $currentLevel * 100;
        $expPercentage = $expNeeded > 0 ? ($currentExp / $expNeeded) * 100 : 0;
        
        $hunger = $pet->hunger ?? 100;
        $happiness = $pet->happiness ?? 80;
        
        return view('student.islands.pet', [
            'user' => $user,
            'group' => $group,
            'pet' => $pet,
            'foodPoints' => $foodPoints,
            'feedHistory' => $feedHistory,
            'currentLevel' => $currentLevel,
            'currentExp' => $currentExp,
            'expNeeded' => $expNeeded,
            'expPercentage' => $expPercentage,
            'hunger' => $hunger,
            'happiness' => $happiness,
        ]);
    }
    
    /**
     * Memberi makan pet
     */
    public function feed(Request $request)
    {
        $request->validate([
            'food_amount' => 'required|integer|min:1|max:100'
        ]);
        
        $user = Auth::user();
        $group = $user->group;
        
        if (!$group) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum memiliki kelompok.'
                ]);
            }
            return redirect()->back()->with('error', 'Anda belum memiliki kelompok.');
        }
        
        $pet = $group->pet;
        
        if (!$pet) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pet belum tersedia.'
                ]);
            }
            return redirect()->back()->with('error', 'Pet belum tersedia.');
        }
        
        $result = $this->petService->feedPet($user, $pet, $request->food_amount);
        
        // Trigger quest "feed_pet" jika berhasil
        if ($result['success']) {
            $this->questService->checkAndCompleteQuest($user, 'feed_pet');
        }
        
        if ($request->ajax()) {
            return response()->json($result);
        }
        
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
}