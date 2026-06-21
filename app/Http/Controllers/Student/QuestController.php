<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\QuestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestController extends Controller
{
    protected $questService;
    
    public function __construct(QuestService $questService)
    {
        $this->questService = $questService;
    }
    
    public function index()
    {
        $user = Auth::user();
        $quests = $this->questService->getDailyQuests($user);
        
        // Hitung progress statistik
        $completedCount = $quests->where('completed', true)->count();
        $totalCount = $quests->count();
        
        // 🔥 PERBAIKAN: Hitung reward dengan aman
        $totalReward = 0;
        foreach ($quests->where('completed', true) as $quest) {
            // Coba ambil reward_food_points atau reward_points
            $reward = $quest->reward_food_points ?? $quest->reward_points ?? 10;
            $totalReward += $reward;
        }
        
        return view('student.islands.quests', [
            'quests' => $quests,
            'completedCount' => $completedCount,
            'totalCount' => $totalCount,
            'totalReward' => $totalReward,
        ]);
    }
    
    public function complete(Request $request, $questId)
    {
        $user = Auth::user();
        
        $result = $this->questService->completeQuest($user, $questId);
        
        if ($request->ajax()) {
            return response()->json($result);
        }
        
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }
        
        return redirect()->back()->with('info', 'Quest akan otomatis selesai saat Anda melakukan aksi yang sesuai.');
    }
    
    public function progress()
    {
        $user = Auth::user();
        $quests = $this->questService->getDailyQuests($user);
        
        $totalReward = 0;
        foreach ($quests->where('completed', true) as $quest) {
            $reward = $quest->reward_food_points ?? $quest->reward_points ?? 10;
            $totalReward += $reward;
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'quests' => $quests,
                'completed_count' => $quests->where('completed', true)->count(),
                'total_count' => $quests->count(),
                'total_reward' => $totalReward,
            ]
        ]);
    }
}