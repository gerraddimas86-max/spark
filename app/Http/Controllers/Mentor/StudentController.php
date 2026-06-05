<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Role;
use App\Models\UserQuest;
use App\Models\CftAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    // Ambil kelompok-kelompok mentor ini
    private function getMyGroupIds()
    {
        return Auth::user()->mentorGroups->pluck('id')->toArray();
    }
    
    public function index()
    {
        $groupIds = $this->getMyGroupIds();
        $groups = Auth::user()->mentorGroups;
        
        $students = User::whereHas('role', function($q) {
                $q->where('name', 'mahasiswa');
            })
            ->whereIn('group_id', $groupIds)
            ->with('group')
            ->get();
        
        // Hitung quest selesai hari ini untuk setiap siswa
        $today = now()->toDateString();
        foreach ($students as $student) {
            $student->quests_completed_today = UserQuest::where('user_id', $student->id)
                ->where('quest_date', $today)
                ->where('is_completed', true)
                ->count();
            
            $student->cft_completed = CftAttempt::where('user_id', $student->id)
                ->where('is_correct', true)
                ->count();
        }
        
        return view('mentor.students.index', compact('students', 'groups'));
    }
    
    public function create()
    {
        $groups = Auth::user()->mentorGroups;
        return view('mentor.students.create', compact('groups'));
    }
    
    public function store(Request $request)
    {
        $groupIds = $this->getMyGroupIds();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users',
            'group_id' => 'required|in:' . implode(',', $groupIds),
            'password' => 'required|string|min:6',
        ]);
        
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        
        User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'password' => Hash::make($request->password),
            'role_id' => $mahasiswaRole->id,
            'group_id' => $request->group_id,
            'food_points' => 0,
            'is_active' => true,
        ]);
        
        return redirect()->route('mentor.students.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }
    
    public function edit(User $user)
    {
        // Pastikan mahasiswa ini berada di kelompok mentor
        $groupIds = $this->getMyGroupIds();
        if (!in_array($user->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
        }
        
        $groups = Auth::user()->mentorGroups;
        return view('mentor.students.edit', compact('user', 'groups'));
    }
    
    public function update(Request $request, User $user)
    {
        $groupIds = $this->getMyGroupIds();
        
        if (!in_array($user->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim,' . $user->id,
            'group_id' => 'required|in:' . implode(',', $groupIds),
            'is_active' => 'boolean',
        ]);
        
        $user->update([
            'name' => $request->name,
            'nim' => $request->nim,
            'group_id' => $request->group_id,
            'is_active' => $request->has('is_active'),
        ]);
        
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        return redirect()->route('mentor.students.index')
            ->with('success', 'Mahasiswa berhasil diupdate');
    }
    
    public function destroy(User $user)
    {
        $groupIds = $this->getMyGroupIds();
        
        if (!in_array($user->group_id, $groupIds)) {
            abort(403, 'Anda tidak memiliki akses ke mahasiswa ini.');
        }
        
        $user->delete();
        
        return redirect()->route('mentor.students.index')
            ->with('success', 'Mahasiswa berhasil dihapus');
    }
    
    public function importForm()
    {
        $groups = Auth::user()->mentorGroups;
        return view('mentor.students.import', compact('groups'));
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);
        
        $groupIds = $this->getMyGroupIds();
        if (!in_array($request->group_id, $groupIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelompok tersebut.');
        }
        
        try {
            $import = new StudentsImport($request->group_id);
            Excel::import($import, $request->file('file'));
            
            $successCount = $import->getSuccessCount();
            $failedCount = $import->getFailedCount();
            $errors = $import->getErrors();
            
            $message = "Import selesai! {$successCount} mahasiswa berhasil ditambahkan.";
            if ($failedCount > 0) {
                $message .= " {$failedCount} gagal.";
            }
            
            if (!empty($errors)) {
                $message .= " Error: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " dan " . (count($errors) - 5) . " error lainnya.";
                }
            }
            
            return redirect()->route('mentor.students.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengimport file: ' . $e->getMessage());
        }
    }
}