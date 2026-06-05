<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Quest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_students' => User::whereHas('role', function($q) {
                $q->where('name', 'mahasiswa');
            })->count(),
            'total_mentors' => User::whereHas('role', function($q) {
                $q->where('name', 'mentor');
            })->count(),
            'total_groups' => Group::count(),
            'total_quests' => Quest::count(),
        ];
        
        return view('developer.dashboard', $data);
    }
}