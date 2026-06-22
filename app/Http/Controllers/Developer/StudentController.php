<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::whereHas('role', function ($q) {
            $q->where('name', 'mahasiswa');
        })->with('group')->get();

        return view('developer.students.index', compact('students'));
    }

    public function create()
    {
        $groups = Group::all();

        return view('developer.students.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users',
            'group_id' => 'required|exists:groups,id',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'mahasiswa')->first()->id,
            'group_id' => $request->group_id,
            'food_points' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('developer.students.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit mahasiswa
     */
    public function edit(User $user)
    {
        // Mengambil semua data kelompok untuk dropdown di form
        $groups = Group::all();

        // Oper data mahasiswa ($user) dan data kelompok ($groups) ke view
        return view('developer.students.edit', compact('user', 'groups'));
    }

    /**
     * Memproses update data mahasiswa
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // NIM harus unik, kecuali untuk user yang sedang diedit saat ini
            'nim' => 'required|string|unique:users,nim,'.$user->id,
            'group_id' => 'required|exists:groups,id',
            // Pasword opsional, kalau diisi minimal 6 karakter
            'password' => 'nullable|string|min:6',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'nim' => $request->nim,
            'group_id' => $request->group_id,
            'is_active' => $request->is_active,
        ];

        // Jika form password diisi, enkripsi lalu masukkan ke array data
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('developer.students.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('developer.students.index')
            ->with('success', 'Mahasiswa berhasil dihapus');
    }
}
