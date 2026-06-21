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
     * Display the login view with random pet and random dialog.
     */
    public function create(): View
    {
        // Data 8 pet dengan 3 dialog masing-masing - TEMA BAJAK LAUT & PKKMB
        $petDialogs = [
            'Phantom' => [
                "Ahoy! Aku Phantom, hantu bajak laut penjaga SPARK! Siap berlayar di PKKMB?",
                "Hai hai! Jangan takut padaku, aku di sini temani petualangan PKKMB-mu!",
                "Selamat datang di kapal SPARK! Aku Phantom, navigator handal untuk mahasiswa baru!"
            ],
            'Captain' => [
                "Wik-wik! Aku Kapten Captain, kapten bajak laut SPARK! Siap tempur PKKMB?",
                "Halo sobat! Ayo kita jelajahi lautan ilmu Fasilkom UNSRI bersama!",
                "Kenaikan semangat! Aku Captain, siap pimpin kamu di PKKMB!"
            ],
            'Finley' => [
                "Salam dari kedalaman laut! Aku Finley, hiu bajak laut SPARK!",
                "Berenanglah bersama ku menuju kesuksesan PKKMB Fasilkom!",
                "Mahasiswa baru yang berani seperti hiu pasti sukses! Ayo kita berlayar!"
            ],
            'Octavius' => [
                "Ahoy! Aku Octavius, gurita bajak laut dengan 8 tangan siap membantu!",
                "Butuh bantuan di PKKMB? 8 tanganku siap membantumu!",
                "Selamat datang di SPARK! Aku Octavius, siap pegang banyak ilmu untukmu!"
            ],
            'Draco' => [
                "Grrr... Aku Draco, naga bajak laut pelindung SPARK! Siap PKKMB?",
                "Apiku membara untuk menyambut mahasiswa baru Fasilkom UNSRI!",
                "Berani seperti naga, raih mimpimu di PKKMB bersama Draco!"
            ],
            'Ember' => [
                "Ceriah! Aku Ember, burung api bajak laut SPARK!",
                "Terbang tinggi di PKKMB Fasilkom UNSRI, aku temani setiap langkahmu!",
                "Semangatmu menyala seperti apiku! Ayo jelajahi PKKMB bersama!"
            ],
            'Shelly' => [
                "Pelan tapi pasti! Aku Shelly, kura-kura bajak laut SPARK!",
                "Perjalanan PKKMB itu panjang, nikmati setiap langkahnya bersamaku!",
                "Konsisten seperti kura-kura, pasti sukses di Fasilkom UNSRI!"
            ],
            'Wally' => [
                "Blub blub! Aku Wally, paus bajak laut ramah SPARK!",
                "Selamat berlayar di lautan ilmu Fasilkom UNSRI bersama Wally!",
                "Halo mahasiswa baru! Aku Wally, siap temani PKKMB-mu!"
            ],
        ];
        
        // Data 8 pet (stage BABY)
        // Untuk Ghost, menggunakan ghost.png (bukan ghost_baby.png) agar animasi kedip bekerja
        $allPets = [
            ['name' => 'Phantom', 'type' => 'ghost', 'image' => 'ghost/ghost.png', 'icon' => 'fa-ghost'],
            ['name' => 'Captain', 'type' => 'parrot', 'image' => 'parrot/parrot_baby.png', 'icon' => 'fa-dove'],
            ['name' => 'Finley', 'type' => 'shark', 'image' => 'shark/shark_baby.png', 'icon' => 'fa-fish'],
            ['name' => 'Octavius', 'type' => 'octopus', 'image' => 'octopus/octopus_baby.png', 'icon' => 'fa-fish'],
            ['name' => 'Draco', 'type' => 'dragon', 'image' => 'dragon/dragon_baby.png', 'icon' => 'fa-dragon'],
            ['name' => 'Ember', 'type' => 'phoenix', 'image' => 'phoenix/phoenix_baby.png', 'icon' => 'fa-fire'],
            ['name' => 'Shelly', 'type' => 'turtle', 'image' => 'turtle/turtle_baby.png', 'icon' => 'fa-fish'],
            ['name' => 'Wally', 'type' => 'whale', 'image' => 'whale/whale_baby.png', 'icon' => 'fa-water'],
        ];
        
        // Pilih pet random
        $selectedIndex = array_rand($allPets);
        $randomPet = $allPets[$selectedIndex];
        
        // Pilih dialog random untuk pet yang terpilih
        $petName = $randomPet['name'];
        $dialogs = $petDialogs[$petName];
        $randomDialog = $dialogs[array_rand($dialogs)];
        $randomPet['dialog'] = $randomDialog;
        
        return view('auth.login', [
            'allPets' => $allPets,
            'randomPet' => $randomPet,
            'selectedIndex' => $selectedIndex,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->login;
        $loginField = is_numeric($loginInput) ? 'nim' : 'email';

        $credentials = [
            $loginField => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleName = $user->role->name;

            return match($roleName) {
                'developer' => redirect()->intended(route('developer.dashboard')),
                'mentor' => redirect()->intended(route('mentor.dashboard')),
                'mahasiswa' => redirect()->intended(route('student.hero')), // LANGSUNG KE HERO
                default => redirect('/'),
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