<?php

namespace App\Services;

use App\Models\User;
use App\Models\Quest;
use App\Models\UserQuest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestService
{
    /**
     * Check and complete quest for a user
     */
    public function checkAndCompleteQuest(User $user, string $questType): bool
    {
        $today = now()->toDateString();
        
        $quest = Quest::where('type', $questType)
                      ->where('is_daily', true)
                      ->first();
        
        if (!$quest) {
            return false;
        }
        
        // Check if already completed today
        $existing = UserQuest::where('user_id', $user->id)
                             ->where('quest_id', $quest->id)
                             ->where('quest_date', $today)
                             ->first();
        
        if ($existing && $existing->is_completed) {
            return false;
        }
        
        // Create or update
        $userQuest = UserQuest::updateOrCreate(
            [
                'user_id' => $user->id,
                'quest_id' => $quest->id,
                'quest_date' => $today,
            ],
            [
                'is_completed' => true,
                'completed_date' => $today,
            ]
        );
        
        // Add food points
        $user->increment('food_points', $quest->food_reward);
        
        Log::info("Quest completed", [
            'user_id' => $user->id,
            'quest_type' => $questType,
            'food_reward' => $quest->food_reward
        ]);
        
        return true;
    }
    
    /**
     * Get daily quests with completion status
     */
    public function getDailyQuests(User $user): array
    {
        $today = now()->toDateString();
        $quests = Quest::where('is_daily', true)->get();
        
        $completedIds = UserQuest::where('user_id', $user->id)
                                 ->where('quest_date', $today)
                                 ->where('is_completed', true)
                                 ->pluck('quest_id')
                                 ->toArray();
        
        $result = [];
        foreach ($quests as $quest) {
            $result[] = [
                'id' => $quest->id,
                'title' => $quest->title,
                'description' => $quest->description,
                'type' => $quest->type,
                'food_reward' => $quest->food_reward,
                'is_completed' => in_array($quest->id, $completedIds)
            ];
        }
        
        return $result;
    }
    
    /**
     * Get completion count for today
     */
    public function getTodayCompletionCount(User $user): int
    {
        $today = now()->toDateString();
        
        return UserQuest::where('user_id', $user->id)
                        ->where('quest_date', $today)
                        ->where('is_completed', true)
                        ->count();
    }
    
    /**
     * Reset daily quests for all users (run via cron)
     */
    public function resetDailyQuests(): void
    {
        // UserQuest records are date-based, so old records remain
        // This method is just a placeholder for cron job
        Log::info("Daily quests reset process completed");
    }
}