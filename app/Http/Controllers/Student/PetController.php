<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\PetService;
use App\Services\QuestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    protected $petService;
    protected $questService;
    
    public function __construct(PetService $petService, QuestService $questService)
    {
        $this->petService = $petService;
        $this->questService = $questService;
    }
    
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;
        
        if (!$group) {
            return view('student.pet', [
                'user' => $user,
                'group' => null,
                'pet' => null,
                'error' => 'Anda belum memiliki kelompok. Hubungi mentor Anda.'
            ]);
        }
        
        $pet = $group->pet;
        
        if (!$pet) {
            return view('student.pet', [
                'user' => $user,
                'group' => $group,
                'pet' => null,
                'error' => 'Pet belum tersedia untuk kelompok ini.'
            ]);
        }
        
        return view('student.pet', compact('user', 'group', 'pet'));
    }
    
    public function feed(Request $request)
    {
        $request->validate([
            'food_amount' => 'required|integer|min:1|max:100'
        ]);
        
        $user = Auth::user();
        $group = $user->group;
        
        if (!$group) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum memiliki kelompok.'
                ]);
            }
            return redirect()->back()->with('error', 'Anda belum memiliki kelompok.');
        }
        
        $pet = $group->pet;
        
        if (!$pet) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pet belum tersedia.'
                ]);
            }
            return redirect()->back()->with('error', 'Pet belum tersedia.');
        }
        
        $result = $this->petService->feedPet($user, $pet, $request->food_amount);
        
        if ($request->ajax()) {
            return response()->json($result);
        }
        
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
}