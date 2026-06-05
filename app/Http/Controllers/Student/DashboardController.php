<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\QuestService;
use App\Services\PetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $questService;
    protected $petService;
    
    public function __construct(QuestService $questService, PetService $petService)
    {
        $this->questService = $questService;
        $this->petService = $petService;
    }
    
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;
        
        // Pet data
        if ($group && $group->pet) {
            $pet = $group->pet;
            $petLevel = $pet->level;
            $petName = $pet->name;
            $petExp = $pet->experience;
            $groupPetHealth = $group->pet_health;
        } else {
            $pet = null;
            $petLevel = 1;
            $petName = 'Baby Pet';
            $petExp = 0;
            $groupPetHealth = 0;
        }
        
        // Quest data
        $dailyQuests = collect($this->questService->getDailyQuests($user));
        $questsCompletedToday = $this->questService->getTodayCompletionCount($user);
        
        // CFT data
        $cftCompleted = $user->cftAttempts()
            ->where('is_correct', true)
            ->count();
        
        // Food points
        $foodPoints = $user->food_points;
        
        // Group name
        $groupName = $group ? $group->name : 'Belum ada kelompok';
        
        return view('student.dashboard', compact(
            'user',
            'group',
            'groupName',
            'pet',
            'petLevel',
            'petName',
            'petExp',
            'groupPetHealth',
            'dailyQuests',
            'questsCompletedToday',
            'cftCompleted',
            'foodPoints'
        ));
    }
}