<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MentorController extends Controller
{
    public function index()
    {
        $mentors = User::whereHas('role', function($q) {
            $q->where('name', 'mentor');
        })->with('mentorGroups')->get();
        
        return view('developer.mentors.index', compact('mentors'));
    }
    
    public function create()
    {
        $groups = \App\Models\Group::all();
        return view('developer.mentors.create', compact('groups'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'group_ids' => 'array',
            'group_ids.*' => 'exists:groups,id',
        ]);
        
        $mentor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'mentor')->first()->id,
            'is_active' => true,
        ]);
        
        if ($request->has('group_ids')) {
            $mentor->mentorGroups()->sync($request->group_ids);
        }
        
        return redirect()->route('developer.mentors.index')
            ->with('success', 'Mentor berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $mentor = User::findOrFail($id);
        $groups = \App\Models\Group::all();
        return view('developer.mentors.edit', compact('mentor', 'groups'));
    }
    
    public function update(Request $request, $id)
    {
        $mentor = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'group_ids' => 'array',
        ]);
        
        $mentor->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        if ($request->has('group_ids')) {
            $mentor->mentorGroups()->sync($request->group_ids);
        }
        
        if ($request->filled('password')) {
            $mentor->update(['password' => Hash::make($request->password)]);
        }
        
        return redirect()->route('developer.mentors.index')
            ->with('success', 'Mentor berhasil diupdate');
    }
    
    public function destroy($id)
    {
        $mentor = User::findOrFail($id);
        $mentor->delete();
        
        return redirect()->route('developer.mentors.index')
            ->with('success', 'Mentor berhasil dihapus');
    }
}