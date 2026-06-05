<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;
        $pet = $group ? $group->pet : null;
        
        return view('student.main', compact('user', 'group', 'pet'));
    }
}