<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeroController extends Controller
{
    /**
     * Menampilkan halaman utama (hero section) dengan kapal dan peta
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data user untuk ditampilkan (opsional)
        $data = [
            'user' => $user,
            'group' => $user->group,
            'pet' => $user->group ? $user->group->pet : null,
        ];
        
        return view('student.hero', $data);
    }

    /**
     * Menampilkan halaman peta dengan 4 pulau
     */
    public function map()
    {
        $user = Auth::user();
        
        // Data untuk ditampilkan di peta (opsional)
        $data = [
            'user' => $user,
            'group' => $user->group,
        ];
        
        return view('student.map', $data);
    }
}