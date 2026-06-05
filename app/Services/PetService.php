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
            if ($pet->experience >= 100) {
                $newLevels = floor($pet->experience / 100);
                $pet->level += $newLevels;
                $pet->experience = $pet->experience % 100;
                $leveledUp = true;
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
            
            return [
                'success' => true,
                'message' => 'Pet berhasil diberi makan!',
                'leveled_up' => $leveledUp,
                'new_level' => $pet->level,
                'pet_exp' => $pet->experience,
                'group_health' => $group->pet_health,
                'remaining_food' => $user->food_points
            ];
            
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
        
        return [
            'exists' => true,
            'pet' => $pet,
            'feed_count' => $feedCount,
            'total_food_given' => $totalFoodGiven,
            'recent_feeders' => $recentFeeders,
            'level_progress' => ($pet->experience / 100) * 100,
            'pet_emoji' => $this->getPetEmoji($pet->level)
        ];
    }
    
    /**
     * Get pet emoji based on level
     */
    private function getPetEmoji(int $level): string
    {
        if ($level < 3) return '🐣';
        if ($level < 6) return '🐥';
        return '🦅';
    }
}