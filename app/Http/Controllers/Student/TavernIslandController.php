<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserQuest;
use Illuminate\Support\Facades\DB;

class TavernIslandController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 🔥 PERBAIKAN: Hitung food points dengan aman
        $foodPoints = 0;
        try {
            $columns = DB::getSchemaBuilder()->getColumnListing('user_quests');
            if (in_array('reward_food_points', $columns)) {
                $foodPoints = UserQuest::where('user_id', $user->id)
                    ->where('completed', true)
                    ->sum('reward_food_points');
            } elseif (in_array('reward_points', $columns)) {
                $foodPoints = UserQuest::where('user_id', $user->id)
                    ->where('completed', true)
                    ->sum('reward_points');
            } else {
                $foodPoints = UserQuest::where('user_id', $user->id)
                    ->where('completed', true)
                    ->count() * 10;
            }
        } catch (\Exception $e) {
            $foodPoints = 0;
        }
        
        return view('student.islands.tavern', [
            'user' => $user,
            'foodPoints' => $foodPoints,
        ]);
    }
}