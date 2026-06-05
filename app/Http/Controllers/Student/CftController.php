<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\CftService;
use App\Models\CftChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CftController extends Controller
{
    protected $cftService;
    
    public function __construct(CftService $cftService)
    {
        $this->cftService = $cftService;
    }
    
    public function index()
    {
        $user = Auth::user();
        $data = $this->cftService->getChallengesWithStatus($user);
        
        // Extract data for view
        $challenges = collect($data['challenges']);
        $completedCount = $data['completed_count'];
        $totalCount = $data['total_count'];
        $totalPoints = $data['total_points'];
        
        return view('student.cft', compact('challenges', 'completedCount', 'totalCount', 'totalPoints'));
    }
    
    public function show(CftChallenge $challenge)
    {
        $user = Auth::user();
        
        // Check if already completed
        $isCompleted = $user->cftAttempts()
            ->where('challenge_id', $challenge->id)
            ->where('is_correct', true)
            ->exists();
        
        if ($isCompleted) {
            return redirect()->route('student.cft')
                ->with('info', 'Challenge ini sudah kamu selesaikan!');
        }
        
        if (!$challenge->is_active) {
            return redirect()->route('student.cft')
                ->with('error', 'Challenge ini sedang tidak aktif.');
        }
        
        return view('student.cft_show', compact('challenge'));
    }
    
    public function submit(Request $request, CftChallenge $challenge)
    {
        $request->validate([
            'answer' => 'required|string|max:500'
        ]);
        
        $user = Auth::user();
        
        // Check if already completed
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
            return redirect()->route('student.cft')
                ->with('error', 'Challenge ini sudah pernah kamu selesaikan!');
        }
        
        $result = $this->cftService->submitAnswer($user, $challenge, $request->answer);
        
        if ($request->ajax()) {
            return response()->json($result);
        }
        
        if ($result['success']) {
            return redirect()->route('student.cft')
                ->with('success', $result['message']);
        }
        
        return redirect()->back()
            ->with('error', $result['message'])
            ->withInput();
    }
}