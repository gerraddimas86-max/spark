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
        // Data 8 pet dengan dialog yang nyambung satu sama lain - TEMA BAJAK LAUT & PKKMB
        // Setiap pet berbicara bergantian membentuk cerita yang utuh
        $petDialogs = [
            'Aragita' => [
                "Ahoy, para bajak laut muda! Aku Aragita, gurita dengan 8 tangan siap membantu! Sebelum kita berlayar, ada yang ingin kami sampaikan...",
                "Halo lagi! Aku Aragita, gurita penjaga SPARK. Mari kita sambut para kapten baru dengan semangat PKKMB!",
                "Selamat datang di kapal SPARK! Aku Aragita, navigator setia. Sudah siapkah kau untuk petualangan ini?"
            ],
            'Deshark' => [
                "Salam dari kedalaman! Aku Deshark, hiu penjelajah SPARK! Kami semua dari berbagai penjuru laut berkumpul di sini...",
                "Berenanglah bersama kami! Aku Deshark, hiu pemberani. Dengarkan cerita dari kami semua tentang PKKMB!",
                "Hai para perenang handal! Aku Deshark, di sini bersama semua teman-temanku untuk menyambut kalian!"
            ],
            'Yuyuu' => [
                "Hiii... Aku Yuyuu, hantu laut penjaga gerbang SPARK! Sebelum kita berlayar, ada yang ingin kami sampaikan...",
                "Halo lagi! Aku Yuyuu, hantu penjaga SPARK. Mari kita sambut para kapten baru dengan semangat PKKMB!",
                "Selamat datang di kapal SPARK! Aku Yuyuu, navigator setia. Sudah siapkah kau untuk petualangan ini?"
            ],
            'Gerriz' => [
                "Klik klik! Aku Gerriz, kepiting penjaga pantai SPARK! Kami semua menjaga semangat PKKMB tetap menyala...",
                "Jepitannya kuat! Aku Gerriz, bersama para pet lain kami akan menjagamu di setiap langkah PKKMB!",
                "Berani seperti kepiting! Aku Gerriz, dan kami semua di sini adalah pelindung mahasiswa baru Fasilkom!"
            ],
            'Wiboo' => [
                "Wik-wik! Aku Wiboo, burung petualang SPARK! Perhatikan baik-baik pesan dari kami semua ya!",
                "Halo sobat! Aku Wiboo, komandan burung SPARK. Kami semua sudah berkumpul untuk menyambut kalian!",
                "Terbang tinggi! Aku Wiboo, siap memimpin kalian memahami setiap pesan dari kami para pet!"
            ],
            'Ridly' => [
                "Hiii! Aku Ridly, kuda laut yang lincah! Perhatikan baik-baik, kami semua punya pesan untukmu...",
                "Berenang lincah! Aku Ridly, bersama teman-teman lain kami akan menerangi perjalanan PKKMB-mu!",
                "Semangatmu mengalir! Aku Ridly, dan kami semua di sini siap menyambut kalian dengan hangat!"
            ],
            'Zarsy' => [
                "Pelan tapi pasti! Aku Zarsy, kura-kura bijak SPARK. Kami semua ingin mengatakan satu hal kepada kalian...",
                "Perjalanan panjang menanti! Aku Zarsy, bersama yang lain kami akan menemani setiap langkahmu di PKKMB!",
                "Konsisten seperti kura-kura! Aku Zarsy, dan kami semua adalah teman setia mahasiswa baru Fasilkom!"
            ],
            'Thala' => [
                "Blub blub! Aku Thala, ikan buntal yang selalu ceria! Kami semua sudah siap menemanimu di PKKMB...",
                "Mengembang seperti buntal! Aku Thala, bersama yang lain kami akan membimbingmu dengan penuh semangat!",
                "Selamat datang di SPARK! Aku Thala, dan kami semua di sini adalah keluarga bajak laut PKKMB!"
            ],
        ];
        
        // Data 8 pet (stage DEWASA) - sesuai dengan folder images/pets
        $allPets = [
            ['name' => 'Aragita', 'type' => 'octopus', 'image' => 'gurita/dewasa/gurita.png', 'icon' => 'fa-octopus-deploy'],
            ['name' => 'Deshark', 'type' => 'shark', 'image' => 'hiu/dewasa/hiu.png', 'icon' => 'fa-shark'],
            ['name' => 'Yuyuu', 'type' => 'ghost', 'image' => 'hantu/dewasa/hantu.png', 'icon' => 'fa-ghost'],
            ['name' => 'Gerriz', 'type' => 'crab', 'image' => 'kepiting/dewasa/kepiting.png', 'icon' => 'fa-crab'],
            ['name' => 'Wiboo', 'type' => 'parrot', 'image' => 'burung-beo/dewasa/burung-beo.png', 'icon' => 'fa-crow'],
            ['name' => 'Ridly', 'type' => 'seahorse', 'image' => 'kuda-laut/dewasa/kuda-laut.png', 'icon' => 'fa-horse-head'],
            ['name' => 'Zarsy', 'type' => 'turtle', 'image' => 'kura-kura/dewasa/kura-kura.png', 'icon' => 'fa-turtle'],
            ['name' => 'Thala', 'type' => 'pufferfish', 'image' => 'ikan-buntal/dewasa/ikan-buntal.png', 'icon' => 'fa-fish'],
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
                'mahasiswa' => redirect()->intended(route('student.hero')),
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