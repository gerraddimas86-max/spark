<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pet;
use App\Models\Group;
use App\Models\PetFeedLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetService
{
    /**
     * Feed the pet
     */
    public function feedPet(User $user, Pet $pet, int $foodAmount): array
    {
        if ($user->food_points < $foodAmount) {
            return [
                'success' => false,
                'message' => 'Food points tidak mencukupi!'
            ];
        }
        
        if ($foodAmount <= 0) {
            return [
                'success' => false,
                'message' => 'Jumlah makanan harus lebih dari 0!'
            ];
        }
        
        DB::beginTransaction();
        
        try {
            // Deduct food points
            $user->decrement('food_points', $foodAmount);
            
            // Add experience to pet
            $oldLevel = $pet->level;
            $pet->increment('experience', $foodAmount);
            
            // Check level up (100 exp per level)
            $leveledUp = false;
            $stageChanged = false;
            $oldStage = $pet->stage;
            $newStage = null;
            
            if ($pet->experience >= 100) {
                $newLevels = floor($pet->experience / 100);
                $pet->level += $newLevels;
                $pet->experience = $pet->experience % 100;
                $leveledUp = true;
                
                // Update stage based on new level
                $stageChanged = $this->updatePetStage($pet);
                if ($stageChanged) {
                    $newStage = $pet->stage;
                }
            }
            
            $pet->save();
            
            // Update group pet health
            $group = $pet->group;
            $newHealth = min(100, $group->pet_health + ($foodAmount / 2));
            $group->pet_health = $newHealth;
            $group->save();
            
            // Log feeding
            PetFeedLog::create([
                'user_id' => $user->id,
                'pet_id' => $pet->id,
                'food_amount' => $foodAmount,
            ]);
            
            DB::commit();
            
            // Trigger quest completion if needed
            $questService = app(QuestService::class);
            $questService->checkAndCompleteQuest($user, 'feed_pet');
            
            $result = [
                'success' => true,
                'message' => 'Pet berhasil diberi makan!',
                'leveled_up' => $leveledUp,
                'new_level' => $pet->level,
                'pet_exp' => $pet->experience,
                'group_health' => $group->pet_health,
                'remaining_food' => $user->food_points
            ];
            
            // Add stage change info if applicable
            if ($stageChanged) {
                $result['stage_changed'] = true;
                $result['old_stage'] = $oldStage;
                $result['new_stage'] = $newStage;
                $result['stage_name'] = $this->getStageName($newStage);
                $result['message'] .= " Pet Anda berevolusi menjadi {$this->getStageName($newStage)}! 🎉";
            }
            
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to feed pet', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'pet_id' => $pet->id
            ]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi.'
            ];
        }
    }
    
    /**
     * Update pet stage based on current level
     * Stage rules:
     * - Level 0: egg
     * - Level 1-4: baby
     * - Level 5-9: adult
     * - Level 10+: legendary
     */
    public function updatePetStage(Pet $pet): bool
    {
        $oldStage = $pet->stage;
        $newStage = $pet->stage;
        
        if ($pet->level == 0) {
            $newStage = 'egg';
        } elseif ($pet->level >= 1 && $pet->level <= 4) {
            $newStage = 'baby';
        } elseif ($pet->level >= 5 && $pet->level <= 9) {
            $newStage = 'adult';
        } elseif ($pet->level >= 10) {
            $newStage = 'legendary';
        }
        
        if ($oldStage !== $newStage) {
            $pet->stage = $newStage;
            $pet->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Get stage name in Indonesian
     */
    public function getStageName(string $stage): string
    {
        return match($stage) {
            'egg' => '🥚 Telur',
            'baby' => '🐣 Bayi',
            'adult' => '🦅 Dewasa',
            'legendary' => '👑 Legendaris',
            default => '🐾 Unknown',
        };
    }
    
    /**
     * Get stage badge color
     */
    public function getStageBadgeColor(string $stage): string
    {
        return match($stage) {
            'egg' => 'bg-gray-600',
            'baby' => 'bg-blue-600',
            'adult' => 'bg-purple-600',
            'legendary' => 'bg-yellow-600',
            default => 'bg-gray-500',
        };
    }
    
    /**
     * Get pet details with stats
     */
    public function getPetDetails(Group $group): array
    {
        $pet = $group->pet;
        
        if (!$pet) {
            return [
                'exists' => false,
                'message' => 'Pet belum tersedia untuk kelompok ini'
            ];
        }
        
        $feedCount = $pet->feedLogs()->count();
        $totalFoodGiven = $pet->feedLogs()->sum('food_amount');
        $recentFeeders = $pet->feedLogs()
                              ->with('user')
                              ->latest()
                              ->take(5)
                              ->get();
        
        // Get next stage info
        $nextStageInfo = $this->getNextStageInfo($pet->level, $pet->stage);
        
        return [
            'exists' => true,
            'pet' => $pet,
            'feed_count' => $feedCount,
            'total_food_given' => $totalFoodGiven,
            'recent_feeders' => $recentFeeders,
            'level_progress' => ($pet->experience / 100) * 100,
            'pet_emoji' => $pet->emoji,
            'stage_name' => $this->getStageName($pet->stage),
            'stage_badge_color' => $this->getStageBadgeColor($pet->stage),
            'next_stage_info' => $nextStageInfo
        ];
    }
    
    /**
     * Get information about next stage
     */
    private function getNextStageInfo(int $currentLevel, string $currentStage): ?array
    {
        if ($currentStage === 'legendary') {
            return null;
        }
        
        $nextStageMap = [
            'egg' => ['stage' => 'baby', 'level_required' => 1, 'name' => '🐣 Bayi'],
            'baby' => ['stage' => 'adult', 'level_required' => 5, 'name' => '🦅 Dewasa'],
            'adult' => ['stage' => 'legendary', 'level_required' => 10, 'name' => '👑 Legendaris'],
        ];
        
        $nextStage = $nextStageMap[$currentStage] ?? null;
        
        if ($nextStage) {
            $levelsNeeded = max(0, $nextStage['level_required'] - $currentLevel);
            return [
                'stage' => $nextStage['stage'],
                'name' => $nextStage['name'],
                'level_required' => $nextStage['level_required'],
                'levels_needed' => $levelsNeeded,
            ];
        }
        
        return null;
    }
}