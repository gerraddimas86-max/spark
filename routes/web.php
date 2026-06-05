<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ============= GUEST ROUTES =============
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes (bawaan Breeze)
require __DIR__.'/auth.php';

// ============= PROTECTED ROUTES (Harus Login) =============
Route::middleware(['auth'])->group(function () {
    
    // Profile routes (semua role bisa akses)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ============= DEVELOPER ROUTES =============
    Route::middleware(['role:developer'])->prefix('developer')->name('developer.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Developer\DashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Mentor (CRUD lengkap)
        Route::resource('/mentors', App\Http\Controllers\Developer\MentorController::class);
        
        // Manajemen Kelompok (CRUD lengkap)
        Route::resource('/groups', App\Http\Controllers\Developer\GroupController::class);
        
        // Manajemen Mahasiswa (CRUD LENGKAP dengan edit & update)
        Route::get('/students', [App\Http\Controllers\Developer\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [App\Http\Controllers\Developer\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [App\Http\Controllers\Developer\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{user}/edit', [App\Http\Controllers\Developer\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{user}', [App\Http\Controllers\Developer\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{user}', [App\Http\Controllers\Developer\StudentController::class, 'destroy'])->name('students.destroy');
        
        // Manajemen Quest (CRUD lengkap)
        Route::resource('/quests', App\Http\Controllers\Developer\QuestController::class);
        
        // Manajemen CFT (CRUD lengkap)
        Route::resource('/cft', App\Http\Controllers\Developer\CftController::class);
        
        // Lihat semua pet
        Route::get('/pets', [App\Http\Controllers\Developer\PetController::class, 'index'])->name('pets.index');
    });
    
    // ============= MENTOR ROUTES =============
    Route::middleware(['role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Mentor\DashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Mahasiswa (CRUD untuk kelompoknya sendiri)
        Route::get('/students', [App\Http\Controllers\Mentor\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [App\Http\Controllers\Mentor\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [App\Http\Controllers\Mentor\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/import', [App\Http\Controllers\Mentor\StudentController::class, 'importForm'])->name('students.import.form');
        Route::post('/students/import', [App\Http\Controllers\Mentor\StudentController::class, 'import'])->name('students.import');
        Route::get('/students/{user}/edit', [App\Http\Controllers\Mentor\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{user}', [App\Http\Controllers\Mentor\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{user}', [App\Http\Controllers\Mentor\StudentController::class, 'destroy'])->name('students.destroy');
        
        // Progress Pet
        Route::get('/pet-progress', [App\Http\Controllers\Mentor\PetController::class, 'progress'])->name('pet.progress');
        
        // Progress Quest Mahasiswa
        Route::get('/quests-progress', [App\Http\Controllers\Mentor\QuestProgressController::class, 'index'])->name('quests.progress');
        
        // Pengumuman (CRUD lengkap)
        Route::resource('/announcements', App\Http\Controllers\Mentor\AnnouncementController::class);
    });
    
    // ============= MAHASISWA ROUTES =============
    Route::middleware(['role:mahasiswa'])->prefix('student')->name('student.')->group(function () {
        // Tampilan utama dengan Three.js
        Route::get('/main', [App\Http\Controllers\Student\MainController::class, 'index'])->name('main');
        
        // Halaman Pet (bisa dipanggil via popup atau langsung)
        Route::get('/pet', [App\Http\Controllers\Student\PetController::class, 'index'])->name('pet');
        Route::post('/pet/feed', [App\Http\Controllers\Student\PetController::class, 'feed'])->name('pet.feed');
        
        // Halaman Quest
        Route::get('/quests', [App\Http\Controllers\Student\QuestController::class, 'index'])->name('quests');
        Route::post('/quests/{quest}/complete', [App\Http\Controllers\Student\QuestController::class, 'complete'])->name('quests.complete');
        
        // Halaman CFT
        Route::get('/cft', [App\Http\Controllers\Student\CftController::class, 'index'])->name('cft');
        Route::get('/cft/{challenge}', [App\Http\Controllers\Student\CftController::class, 'show'])->name('cft.show');
        Route::post('/cft/{challenge}/submit', [App\Http\Controllers\Student\CftController::class, 'submit'])->name('cft.submit');
        
        // Pengumuman
        Route::get('/announcements', [App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('announcements');
        Route::get('/announcements/{announcement}', [App\Http\Controllers\Student\AnnouncementController::class, 'show'])->name('announcements.show');
        
        // Dashboard sederhana (ringkasan)
        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    });
    
});

// ============= REDIRECT DASHBOARD (untuk menghindari error) =============
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    $role = auth()->user()->role->name;
    return match($role) {
        'developer' => redirect()->route('developer.dashboard'),
        'mentor' => redirect()->route('mentor.dashboard'),
        'mahasiswa' => redirect()->route('student.main'),
        default => redirect('/'),
    };
})->name('dashboard');