<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Cek apakah user punya role yang diizinkan
        if (!in_array($user->role->name, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cek apakah user aktif (untuk mahasiswa)
        if ($user->role->name === 'mahasiswa' && !$user->is_active) {
            Auth::logout();
            abort(403, 'Akun Anda dinonaktifkan. Silakan hubungi mentor.');
        }

        return $next($request);
    }
}