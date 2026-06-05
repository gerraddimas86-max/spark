<?php

namespace App\Services;

use App\Models\User;
use App\Models\CftChallenge;
use App\Models\CftAttempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CftService
{
    /**
     * Submit answer for a CFT challenge
     */
    public function submitAnswer(User $user, CftChallenge $challenge, string $answer): array
    {
        // Check if already completed
        $existing = CftAttempt::where('user_id', $user->id)
                              ->where('challenge_id', $challenge->id)
                              ->where('is_correct', true)
                              ->first();
        
        if ($existing) {
            return [
                'success' => false,
                'message' => 'Challenge ini sudah pernah kamu selesaikan!'
            ];
        }
        
        // Check if challenge is active
        if (!$challenge->is_active) {
            return [
                'success' => false,
                'message' => 'Challenge ini sedang tidak aktif.'
            ];
        }
        
        // Compare answer (case-sensitive, trim whitespace)
        $isCorrect = trim($answer) === $challenge->flag;
        
        DB::beginTransaction();
        
        try {
            // Record attempt
            $attempt = CftAttempt::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'challenge_id' => $challenge->id,
                ],
                [
                    'answer' => $answer,
                    'is_correct' => $isCorrect,
                ]
            );
            
            if ($isCorrect) {
                // Add food points
                $user->increment('food_points', $challenge->food_reward);
                
                // Trigger quest completion
                $questService = app(QuestService::class);
                $questService->checkAndCompleteQuest($user, 'cft');
            }
            
            DB::commit();
            
            if ($isCorrect) {
                Log::info("CFT challenge completed", [
                    'user_id' => $user->id,
                    'challenge_id' => $challenge->id,
                    'challenge_title' => $challenge->title
                ]);
                
                return [
                    'success' => true,
                    'message' => "✅ Selamat! Jawaban benar. Kamu mendapat {$challenge->food_reward} food points!",
                    'food_reward' => $challenge->food_reward,
                    'points' => $challenge->points
                ];
            } else {
                return [
                    'success' => false,
                    'message' => '❌ Jawaban salah. Coba lagi!'
                ];
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to submit CFT answer', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'challenge_id' => $challenge->id
            ]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi.'
            ];
        }
    }
    
    /**
     * Get all challenges with completion status for user
     */
    public function getChallengesWithStatus(User $user): array
    {
        $challenges = CftChallenge::where('is_active', true)->get();
        
        $completedIds = CftAttempt::where('user_id', $user->id)
                                  ->where('is_correct', true)
                                  ->pluck('challenge_id')
                                  ->toArray();
        
        $completedCount = count($completedIds);
        $totalPoints = CftAttempt::where('user_id', $user->id)
                                 ->where('is_correct', true)
                                 ->join('cft_challenges', 'cft_attempts.challenge_id', '=', 'cft_challenges.id')
                                 ->sum('cft_challenges.points');
        
        $challengesArray = [];
        foreach ($challenges as $challenge) {
            $challengesArray[] = [
                'id' => $challenge->id,
                'title' => $challenge->title,
                'description' => $challenge->description,
                'food_reward' => $challenge->food_reward,
                'points' => $challenge->points,
                'is_completed' => in_array($challenge->id, $completedIds)
            ];
        }
        
        return [
            'challenges' => $challengesArray,
            'completed_count' => $completedCount,
            'total_count' => $challenges->count(),
            'total_points' => $totalPoints
        ];
    }
    
    /**
     * Get leaderboard data
     */
    public function getLeaderboard($limit = 10): array
    {
        $users = User::whereHas('role', function($q) {
                $q->where('name', 'mahasiswa');
            })
            ->with('group')
            ->get();
        
        $leaderboard = [];
        foreach ($users as $user) {
            $totalPoints = CftAttempt::where('user_id', $user->id)
                ->where('is_correct', true)
                ->join('cft_challenges', 'cft_attempts.challenge_id', '=', 'cft_challenges.id')
                ->sum('cft_challenges.points');
            
            $leaderboard[] = [
                'user' => $user,
                'total_points' => $totalPoints,
                'group_name' => $user->group->name ?? '-'
            ];
        }
        
        // Sort by points descending
        usort($leaderboard, function($a, $b) {
            return $b['total_points'] <=> $a['total_points'];
        });
        
        return array_slice($leaderboard, 0, $limit);
    }
}