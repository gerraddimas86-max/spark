<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\CftService;
use App\Services\QuestService;
use App\Models\CftChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SumarjaController extends Controller
{
    protected $cftService;
    protected $questService;

    public function __construct(CftService $cftService, QuestService $questService)
    {
        $this->cftService = $cftService;
        $this->questService = $questService;
    }

    public function index()
    {
        $user = Auth::user();

        // ========== DATA CTF ==========
        $cftData = $this->cftService->getChallengesWithStatus($user);
        
        // Ubah array jadi object
        $challenges = collect($cftData['challenges'])->map(function($item) {
            return (object) $item;
        });
        
        $completedCount = $cftData['completed_count'] ?? 0;
        $totalCount = $cftData['total_count'] ?? 0;
        $totalPoints = $cftData['total_points'] ?? 0;

        // ========== DATA QUEST ==========
        $questsData = $this->questService->getDailyQuests($user);
        
        // Ubah array jadi object
        $quests = collect($questsData)->map(function($item) {
            return (object) $item;
        });
        
        $questCompletedCount = $quests->where('is_completed', true)->count();
        $questTotalCount = $quests->count();

        // Hitung total reward quest
        $questTotalReward = 0;
        foreach ($quests->where('is_completed', true) as $quest) {
            $reward = $quest->reward_food_points ?? $quest->food_reward ?? 10;
            $questTotalReward += $reward;
        }

        return view('student.islands.sumarja.index', compact(
            'challenges',
            'completedCount',
            'totalCount',
            'totalPoints',
            'quests',
            'questCompletedCount',
            'questTotalCount',
            'questTotalReward'
        ));
    }

    public function show(CftChallenge $challenge)
    {
        $user = Auth::user();

        $isCompleted = $user->cftAttempts()
            ->where('challenge_id', $challenge->id)
            ->where('is_correct', true)
            ->exists();

        if ($isCompleted) {
            return redirect()->route('student.island.sumarja')
                ->with('info', 'Challenge ini sudah kamu selesaikan!');
        }

        if (!$challenge->is_active) {
            return redirect()->route('student.island.sumarja')
                ->with('error', 'Challenge ini sedang tidak aktif.');
        }

        return view('student.islands.sumarja.show', compact('challenge'));
    }

    public function submit(Request $request, CftChallenge $challenge)
    {
        $request->validate([
            'answer' => 'required|string|max:500'
        ]);

        $user = Auth::user();

        $isCompleted = $user->cftAttempts()
            ->where('challenge_id', $challenge->id)
            ->where('is_correct', true)
            ->exists();

        if ($isCompleted) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Challenge ini sudah pernah kamu selesaikan!'
                ]);
            }
            return redirect()->route('student.island.sumarja')
                ->with('error', 'Challenge ini sudah pernah kamu selesaikan!');
        }

        $result = $this->cftService->submitAnswer($user, $challenge, $request->answer);

        if ($request->ajax()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->route('student.island.sumarja')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message'])
            ->withInput();
    }

    public function completeQuest(Request $request, $questId)
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

    public function questProgress()
    {
        $user = Auth::user();
        $questsData = $this->questService->getDailyQuests($user);
        
        $quests = collect($questsData)->map(function($item) {
            return (object) $item;
        });

        $totalReward = 0;
        foreach ($quests->where('is_completed', true) as $quest) {
            $reward = $quest->reward_food_points ?? $quest->food_reward ?? 10;
            $totalReward += $reward;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'quests' => $quests,
                'completed_count' => $quests->where('is_completed', true)->count(),
                'total_count' => $quests->count(),
                'total_reward' => $totalReward,
            ]
        ]);
    }
}