<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Quest;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    public function index()
    {
        $quests = Quest::all();
        return view('developer.quests.index', compact('quests'));
    }
    
    public function create()
    {
        return view('developer.quests.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:login,cft,feed_pet,read_announcement,custom',
            'food_reward' => 'required|integer|min:1',
            'is_daily' => 'boolean',
        ]);
        
        Quest::create($request->all());
        
        return redirect()->route('developer.quests.index')
            ->with('success', 'Quest berhasil dibuat');
    }
    
    public function edit(Quest $quest)
    {
        return view('developer.quests.edit', compact('quest'));
    }
    
    public function update(Request $request, Quest $quest)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:login,cft,feed_pet,read_announcement,custom',
            'food_reward' => 'required|integer|min:1',
            'is_daily' => 'boolean',
        ]);
        
        $quest->update($request->all());
        
        return redirect()->route('developer.quests.index')
            ->with('success', 'Quest berhasil diupdate');
    }
    
    public function destroy(Quest $quest)
    {
        $quest->delete();
        
        return redirect()->route('developer.quests.index')
            ->with('success', 'Quest berhasil dihapus');
    }
}