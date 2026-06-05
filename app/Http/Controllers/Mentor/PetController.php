<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\PetFeedLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function progress()
    {
        $groups = Auth::user()->mentorGroups;
        
        // Load pet data for each group
        foreach ($groups as $group) {
            $group->load('pet.feedLogs.user');
            
            // Calculate additional stats
            if ($group->pet) {
                $group->pet->feed_count = $group->pet->feedLogs->count();
                $group->pet->total_food_given = $group->pet->feedLogs->sum('food_amount');
                $group->pet->level_progress = ($group->pet->experience / 100) * 100;
                $group->pet->recent_feeders = $group->pet->feedLogs()
                    ->with('user')
                    ->latest()
                    ->take(5)
                    ->get();
            }
        }
        
        return view('mentor.pet-progress', compact('groups'));
    }
}