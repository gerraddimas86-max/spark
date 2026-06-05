<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Quest;
use App\Models\UserQuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestProgressController extends Controller
{
    private function getMyGroupIds()
    {
        return Auth::user()->mentorGroups->pluck('id')->toArray();
    }
    
    public function index()
    {
        $groupIds = $this->getMyGroupIds();
        $groups = Auth::user()->mentorGroups;
        $quests = Quest::where('is_daily', true)->get();
        $today = now()->toDateString();
        
        // Get all students under mentor's groups
        $students = User::whereHas('role', function($q) {
                $q->where('name', 'mahasiswa');
            })
            ->whereIn('group_id', $groupIds)
            ->with('group')
            ->get();
        
        // Load quest completions for today
        foreach ($students as $student) {
            $completedQuests = UserQuest::where('user_id', $student->id)
                ->where('quest_date', $today)
                ->where('is_completed', true)
                ->pluck('quest_id')
                ->toArray();
            
            $student->completed_quests = $completedQuests;
            $student->completion_count = count($completedQuests);
        }
        
        // Calculate statistics
        $totalStudents = $students->count();
        $completedToday = 0;
        $totalFoodGiven = 0;
        
        foreach ($students as $student) {
            $completedToday += $student->completion_count;
            $totalFoodGiven += $student->food_points;
        }
        
        $completionRate = $totalStudents > 0 ? round(($completedToday / ($totalStudents * $quests->count())) * 100, 1) : 0;
        
        return view('mentor.quests-progress', compact(
            'groups',
            'students',
            'quests',
            'totalStudents',
            'completedToday',
            'completionRate',
            'totalFoodGiven'
        ));
    }
    
    public function showStudent(User $student)
    {
        $groupIds = $this->getMyGroupIds();
        
        // Check if student belongs to mentor's groups
        if (!in_array($student->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
        }
        
        $quests = Quest::where('is_daily', true)->get();
        $today = now()->toDateString();
        
        // Get quest completion history
        $questHistory = UserQuest::where('user_id', $student->id)
            ->where('is_completed', true)
            ->with('quest')
            ->latest()
            ->take(20)
            ->get();
        
        // Get today's completions
        $todayCompletions = UserQuest::where('user_id', $student->id)
            ->where('quest_date', $today)
            ->where('is_completed', true)
            ->pluck('quest_id')
            ->toArray();
        
        // Calculate total food earned
        $totalFoodEarned = UserQuest::where('user_id', $student->id)
            ->where('is_completed', true)
            ->join('quests', 'user_quests.quest_id', '=', 'quests.id')
            ->sum('quests.food_reward');
        
        return view('mentor.students.quest-detail', compact(
            'student',
            'quests',
            'todayCompletions',
            'questHistory',
            'totalFoodEarned'
        ));
    }
}