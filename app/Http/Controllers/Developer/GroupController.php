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
        
        // Daftar tipe pet yang tersedia (acak atau berurutan)
        $petTypes = ['ghost', 'parrot', 'shark', 'octopus', 'dragon', 'phoenix', 'turtle', 'whale'];
        $randomType = $petTypes[array_rand($petTypes)];
        
        // Daftar nama default berdasarkan tipe
        $defaultNames = [
            'ghost' => 'Phantom',
            'parrot' => 'Captain',
            'shark' => 'Finley',
            'octopus' => 'Octavius',
            'dragon' => 'Draco',
            'phoenix' => 'Ember',
            'turtle' => 'Shelly',
            'whale' => 'Wally',
        ];
        
        // Buat pet untuk kelompok ini (level 0 = telur)
        Pet::create([
            'name' => $defaultNames[$randomType] ?? 'Pet ' . $group->name,
            'group_id' => $group->id,
            'type' => $randomType,
            'level' => 0,
            'experience' => 0,
            'stage' => 'egg',
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
            'pet_name' => 'nullable|string|max:255', // Validasi untuk nama pet
        ]);
        
        // Update kelompok
        $group->update($request->only('name', 'code'));
        
        // Update nama pet jika ada
        if ($request->filled('pet_name') && $group->pet) {
            $group->pet->update([
                'name' => $request->pet_name
            ]);
        }
        
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