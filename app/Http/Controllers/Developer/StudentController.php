<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::whereHas('role', function($q) {
            $q->where('name', 'mahasiswa');
        })->with('group')->get();
        
        return view('developer.students.index', compact('students'));
    }
    
    public function create()
    {
        $groups = Group::all();
        return view('developer.students.create', compact('groups'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users',
            'group_id' => 'required|exists:groups,id',
            'password' => 'required|string|min:6',
        ]);
        
        User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'mahasiswa')->first()->id,
            'group_id' => $request->group_id,
            'food_points' => 0,
            'is_active' => true,
        ]);
        
        return redirect()->route('developer.students.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }
    
    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('developer.students.index')
            ->with('success', 'Mahasiswa berhasil dihapus');
    }
}