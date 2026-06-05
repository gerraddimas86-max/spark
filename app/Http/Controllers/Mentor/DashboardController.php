<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Announcement;
use App\Models\UserQuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil kelompok-kelompok yang dibimbing oleh mentor ini
        $groups = Auth::user()->mentorGroups;
        $groupIds = $groups->pluck('id')->toArray();
        
        // Total mahasiswa bimbingan
        $total_students = User::whereHas('role', function($q) {
                $q->where('name', 'mahasiswa');
            })
            ->whereIn('group_id', $groupIds)
            ->count();
        
        // Total kelompok bimbingan
        $total_groups = $groups->count();
        
        // Total quest selesai hari ini dari semua mahasiswa bimbingan
        $today = now()->toDateString();
        $total_quests_completed_today = UserQuest::whereIn('user_id', function($q) use ($groupIds) {
                $q->select('id')->from('users')->whereIn('group_id', $groupIds);
            })
            ->where('quest_date', $today)
            ->where('is_completed', true)
            ->count();
        
        // Rata-rata pet level
        $avg_pet_level = 1;
        if ($groups->count() > 0) {
            $totalLevel = 0;
            $groupCount = 0;
            foreach ($groups as $group) {
                if ($group->pet) {
                    $totalLevel += $group->pet->level;
                    $groupCount++;
                }
            }
            $avg_pet_level = $groupCount > 0 ? round($totalLevel / $groupCount, 1) : 1;
        }
        
        // Pengumuman terbaru (5 terakhir)
        $recent_announcements = Announcement::whereIn('group_id', $groupIds)
            ->with('group', 'creator')
            ->latest()
            ->take(5)
            ->get();
        
        return view('mentor.dashboard', compact(
            'groups',
            'total_students',
            'total_groups',
            'total_quests_completed_today',
            'avg_pet_level',
            'recent_announcements'
        ));
    }
}