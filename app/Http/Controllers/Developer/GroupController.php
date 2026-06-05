<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Pet;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['mentors', 'students', 'pet'])->get();
        return view('developer.groups.index', compact('groups'));
    }
    
    public function create()
    {
        return view('developer.groups.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:groups',
        ]);
        
        $group = Group::create([
            'name' => $request->name,
            'code' => $request->code,
            'pet_health' => 0,
        ]);
        
        // Buat pet untuk kelompok ini
        Pet::create([
            'name' => 'Pet ' . $group->name,
            'group_id' => $group->id,
            'level' => 1,
            'experience' => 0,
        ]);
        
        return redirect()->route('developer.groups.index')
            ->with('success', 'Kelompok berhasil dibuat');
    }
    
    public function edit(Group $group)
    {
        return view('developer.groups.edit', compact('group'));
    }
    
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:groups,code,' . $group->id,
        ]);
        
        $group->update($request->only('name', 'code'));
        
        return redirect()->route('developer.groups.index')
            ->with('success', 'Kelompok berhasil diupdate');
    }
    
    public function destroy(Group $group)
    {
        $group->delete();
        
        return redirect()->route('developer.groups.index')
            ->with('success', 'Kelompok berhasil dihapus');
    }
}