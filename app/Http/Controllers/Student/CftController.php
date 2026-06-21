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
    
    /**
     * Menampilkan daftar challenge CFT (Pulau CTF)
     */
    public function index()
    {
        $user = Auth::user();
        $data = $this->cftService->getChallengesWithStatus($user);
        
        // Extract data for view
        $challenges = collect($data['challenges']);
        $completedCount = $data['completed_count'];
        $totalCount = $data['total_count'];
        $totalPoints = $data['total_points'];
        
        // 🔄 DIUBAH: dari 'student.cft' ke 'student.islands.ctf'
        return view('student.islands.ctf', compact('challenges', 'completedCount', 'totalCount', 'totalPoints'));
    }
    
    /**
     * Menampilkan detail challenge tertentu
     */
    public function show(CftChallenge $challenge)
    {
        $user = Auth::user();
        
        // Check if already completed
        $isCompleted = $user->cftAttempts()
            ->where('challenge_id', $challenge->id)
            ->where('is_correct', true)
            ->exists();
        
        if ($isCompleted) {
            // 🔄 DIUBAH: redirect ke island.ctf
            return redirect()->route('student.island.ctf')
                ->with('info', 'Challenge ini sudah kamu selesaikan!');
        }
        
        if (!$challenge->is_active) {
            // 🔄 DIUBAH: redirect ke island.ctf
            return redirect()->route('student.island.ctf')
                ->with('error', 'Challenge ini sedang tidak aktif.');
        }
        
        return view('student.cft_show', compact('challenge'));
    }
    
    /**
     * Submit jawaban challenge
     */
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
            // 🔄 DIUBAH: redirect ke island.ctf
            return redirect()->route('student.island.ctf')
                ->with('error', 'Challenge ini sudah pernah kamu selesaikan!');
        }
        
        $result = $this->cftService->submitAnswer($user, $challenge, $request->answer);
        
        if ($request->ajax()) {
            return response()->json($result);
        }
        
        if ($result['success']) {
            // 🔄 DIUBAH: redirect ke island.ctf
            return redirect()->route('student.island.ctf')
                ->with('success', $result['message']);
        }
        
        return redirect()->back()
            ->with('error', $result['message'])
            ->withInput();
    }
}