<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\QuestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    protected $questService;
    
    public function __construct(QuestService $questService)
    {
        $this->questService = $questService;
    }
    
    /**
     * Menampilkan daftar pengumuman untuk kelompok mahasiswa
     */
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;
        
        // Jika mahasiswa belum memiliki kelompok
        if (!$group) {
            return view('student.announcements', [
                'announcements' => collect(),
                'groupName' => null
            ]);
        }
        
        // Ambil semua pengumuman untuk kelompok ini
        $announcements = Announcement::where('group_id', $group->id)
            ->latest()
            ->get();
        
        // Trigger quest "baca pengumuman" jika ada pengumuman
        if ($announcements->count() > 0) {
            $this->questService->checkAndCompleteQuest($user, 'read_announcement');
        }
        
        return view('student.announcements', [
            'announcements' => $announcements,
            'groupName' => $group->name
        ]);
    }
    
    /**
     * Menampilkan detail pengumuman tertentu
     */
    public function show(Announcement $announcement)
    {
        $user = Auth::user();
        $group = $user->group;
        
        // Validasi: pastikan pengumuman milik kelompok yang sama
        if ($announcement->group_id !== ($group->id ?? null)) {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }
        
        return view('student.announcements_show', compact('announcement'));
    }
}