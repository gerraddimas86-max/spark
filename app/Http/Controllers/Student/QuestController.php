<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\QuestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestController extends Controller
{
    protected $questService;
    
    public function __construct(QuestService $questService)
    {
        $this->questService = $questService;
    }
    
    public function index()
    {
        $user = Auth::user();
        $quests = $this->questService->getDailyQuests($user);
        
        return view('student.quests', compact('quests'));
    }
    
    public function complete(Request $request, $questId)
    {
        // This method is for manual quest completion if needed
        // Most quests are auto-completed via service triggers
        
        return redirect()->back()->with('info', 'Quest akan otomatis selesai saat Anda melakukan aksi yang sesuai.');
    }
}