<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\CftChallenge;
use Illuminate\Http\Request;

class CftController extends Controller
{
    public function index()
    {
        $challenges = CftChallenge::all();
        return view('developer.cft.index', compact('challenges'));
    }
    
    public function create()
    {
        return view('developer.cft.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'flag' => 'required|string',
            'food_reward' => 'required|integer|min:1',
            'points' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        CftChallenge::create($request->all());
        
        return redirect()->route('developer.cft.index')
            ->with('success', 'Challenge CFT berhasil dibuat');
    }
    
    public function edit(CftChallenge $cft)
    {
        return view('developer.cft.edit', compact('cft'));
    }
    
    public function update(Request $request, CftChallenge $cft)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'flag' => 'required|string',
            'food_reward' => 'required|integer|min:1',
            'points' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $cft->update($request->all());
        
        return redirect()->route('developer.cft.index')
            ->with('success', 'Challenge CFT berhasil diupdate');
    }
    
    public function destroy(CftChallenge $cft)
    {
        $cft->delete();
        
        return redirect()->route('developer.cft.index')
            ->with('success', 'Challenge CFT berhasil dihapus');
    }
}