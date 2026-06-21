<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeIslandController extends Controller
{
    /**
     * Menampilkan halaman Pulau Home (kembali ke kapal)
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('student.islands.home', [
            'user' => $user,
        ]);
    }
}