<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileIslandController extends Controller
{
    /**
     * Menampilkan halaman profil (Pulau Profil)
     */
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;
        
        return view('student.islands.profile', [
            'user' => $user,
            'group' => $group,
        ]);
    }
    
    /**
     * Update profil (ganti nama)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Nama berhasil diubah!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Nama berhasil diubah!');
    }
    
    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);
        
        $user = Auth::user();
        
        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai!'
                ], 422);
            }
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai!']);
        }
        
        // Update password baru
        $user->password = Hash::make($request->password);
        $user->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
    
    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}