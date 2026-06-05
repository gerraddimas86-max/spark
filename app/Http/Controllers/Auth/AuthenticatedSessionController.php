<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi: field name = 'login'
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cek apakah input berupa NIM (numeric) atau email
        $loginInput = $request->login;
        $loginField = is_numeric($loginInput) ? 'nim' : 'email';

        $credentials = [
            $loginField => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();
            $roleName = $user->role->name;

            return match($roleName) {
                'developer' => redirect()->intended(route('developer.dashboard')),
                'mentor' => redirect()->intended(route('mentor.dashboard')),
                'mahasiswa' => redirect()->intended(route('student.main')),
                default => redirect('/'),  // ← INI YANG DIUBAH (dari 'dashboard' jadi '/')
            };
        }

        return back()->withErrors([
            'login' => 'NIM/Email atau password salah.',
        ])->onlyInput('login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}