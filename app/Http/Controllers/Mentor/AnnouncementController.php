<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    private function getMyGroupIds()
    {
        return Auth::user()->mentorGroups->pluck('id')->toArray();
    }
    
    public function index()
    {
        $groupIds = $this->getMyGroupIds();
        $groups = Auth::user()->mentorGroups;
        
        $announcements = Announcement::whereIn('group_id', $groupIds)
            ->with('group', 'creator')
            ->latest()
            ->get();
        
        return view('mentor.announcements.index', compact('announcements', 'groups'));
    }
    
    public function create()
    {
        $groups = Auth::user()->mentorGroups;
        return view('mentor.announcements.create', compact('groups'));
    }
    
    public function store(Request $request)
    {
        $groupIds = $this->getMyGroupIds();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'group_id' => 'required|in:' . implode(',', $groupIds),
        ]);
        
        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'group_id' => $request->group_id,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('mentor.announcements.index')
            ->with('success', 'Pengumuman berhasil dipublikasikan');
    }
    
    public function show(Announcement $announcement)
    {
        $groupIds = $this->getMyGroupIds();
        
        if (!in_array($announcement->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }
        
        return view('mentor.announcements.show', compact('announcement'));
    }
    
    public function edit(Announcement $announcement)
    {
        $groupIds = $this->getMyGroupIds();
        
        if (!in_array($announcement->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }
        
        $groups = Auth::user()->mentorGroups;
        return view('mentor.announcements.edit', compact('announcement', 'groups'));
    }
    
    public function update(Request $request, Announcement $announcement)
    {
        $groupIds = $this->getMyGroupIds();
        
        if (!in_array($announcement->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'group_id' => 'required|in:' . implode(',', $groupIds),
        ]);
        
        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'group_id' => $request->group_id,
        ]);
        
        return redirect()->route('mentor.announcements.index')
            ->with('success', 'Pengumuman berhasil diupdate');
    }
    
    public function destroy(Announcement $announcement)
    {
        $groupIds = $this->getMyGroupIds();
        
        if (!in_array($announcement->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }
        
        $announcement->delete();
        
        return redirect()->route('mentor.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}