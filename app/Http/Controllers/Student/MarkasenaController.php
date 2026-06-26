<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\QuestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MarkasenaController extends Controller
{
    protected $questService;

    public function __construct(QuestService $questService)
    {
        $this->questService = $questService;
    }

    /**
     * Menampilkan halaman pulau Markasena (Pengumuman sebagai utama)
     */
    public function index()
    {
        $user = Auth::user();
        $group = $user->group;

        // Jika mahasiswa belum memiliki kelompok
        if (!$group) {
            return view('student.islands.markasena.index', [
                'announcements' => collect(),
                'groupName' => null,
                'user' => $user,
                'group' => null
            ]);
        }

        // Ambil semua pengumuman untuk kelompok ini
        $announcements = Announcement::where('group_id', $group->id)
            ->latest()
            ->get();

        // Trigger quest "baca pengumuman" jika ada pengumuman
        if ($announcements->count() > 0) {
            $this->questService->checkAndCompleteQuest($user, 'read_announcement');
        }

        return view('student.islands.markasena.index', [
            'announcements' => $announcements,
            'groupName' => $group->name,
            'user' => $user,
            'group' => $group
        ]);
    }

    /**
     * Menampilkan detail pengumuman tertentu
     */
    public function show(Announcement $announcement)
    {
        $user = Auth::user();
        $group = $user->group;

        // Validasi: pastikan pengumuman milik kelompok yang sama
        if ($announcement->group_id !== ($group->id ?? null)) {
            abort(403, 'Anda tidak memiliki akses ke pengumuman ini.');
        }

        return view('student.islands.markasena.show', compact('announcement'));
    }

    /**
     * Menampilkan halaman profil
     */
    public function profile()
    {
        $user = Auth::user();
        $group = $user->group;

        return view('student.islands.markasena.profile', [
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