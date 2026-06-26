<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KertasakaController extends Controller
{
    /**
     * Menampilkan halaman pulau Kertasaka (Tavern / Minigames)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Data untuk view
        $data = [
            'user' => $user,
            'group' => $user->group,
        ];
        
        return view('student.islands.kertasaka.index', $data);
    }
}