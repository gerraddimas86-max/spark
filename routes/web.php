<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ============= GUEST ROUTES =============
Route::get('/', function () {
    return redirect()->route('login');
});

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
        
        Route::resource('/mentors', App\Http\Controllers\Developer\MentorController::class);
        Route::resource('/groups', App\Http\Controllers\Developer\GroupController::class);
        
        Route::get('/students', [App\Http\Controllers\Developer\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [App\Http\Controllers\Developer\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [App\Http\Controllers\Developer\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{user}/edit', [App\Http\Controllers\Developer\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{user}', [App\Http\Controllers\Developer\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{user}', [App\Http\Controllers\Developer\StudentController::class, 'destroy'])->name('students.destroy');
        
        Route::resource('/quests', App\Http\Controllers\Developer\QuestController::class);
        Route::resource('/cft', App\Http\Controllers\Developer\CftController::class);
        Route::get('/pets', [App\Http\Controllers\Developer\PetController::class, 'index'])->name('pets.index');
    });
    
    // ============= MENTOR ROUTES =============
    Route::middleware(['role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Mentor\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/students', [App\Http\Controllers\Mentor\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [App\Http\Controllers\Mentor\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [App\Http\Controllers\Mentor\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/import', [App\Http\Controllers\Mentor\StudentController::class, 'importForm'])->name('students.import.form');
        Route::post('/students/import', [App\Http\Controllers\Mentor\StudentController::class, 'import'])->name('students.import');
        Route::get('/students/{user}/edit', [App\Http\Controllers\Mentor\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{user}', [App\Http\Controllers\Mentor\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{user}', [App\Http\Controllers\Mentor\StudentController::class, 'destroy'])->name('students.destroy');
        
        Route::get('/pet-progress', [App\Http\Controllers\Mentor\PetController::class, 'progress'])->name('pet.progress');
        Route::get('/quests-progress', [App\Http\Controllers\Mentor\QuestProgressController::class, 'index'])->name('quests.progress');
        Route::resource('/announcements', App\Http\Controllers\Mentor\AnnouncementController::class);
    });
    
    // ============= MAHASISWA ROUTES (4 PULAU) =============
    Route::middleware(['role:mahasiswa'])->prefix('student')->name('student.')->group(function () {
        
        // ============ HERO & MAP ============
        Route::get('/hero', [App\Http\Controllers\Student\HeroController::class, 'index'])->name('hero');
        Route::get('/map', [App\Http\Controllers\Student\HeroController::class, 'map'])->name('map');
        
        // ============ 4 PULAU ============
        
        // 1. MUARALAYA - Pulau Pet
        Route::prefix('muaralaya')->name('muaralaya.')->group(function () {
            Route::get('/', [App\Http\Controllers\Student\MuaralayaController::class, 'index'])->name('index');
            Route::get('/island', [App\Http\Controllers\Student\MuaralayaController::class, 'index'])->name('island');
            Route::post('/feed', [App\Http\Controllers\Student\MuaralayaController::class, 'feed'])->name('feed');
        });
        Route::get('/island/muaralaya', [App\Http\Controllers\Student\MuaralayaController::class, 'index'])->name('island.muaralaya');
        
        // 2. KERTASAKA - Pulau Tavern / Minigames
        Route::prefix('kertasaka')->name('kertasaka.')->group(function () {
            Route::get('/', [App\Http\Controllers\Student\KertasakaController::class, 'index'])->name('index');
            Route::get('/island', [App\Http\Controllers\Student\KertasakaController::class, 'index'])->name('island');
        });
        Route::get('/island/kertasaka', [App\Http\Controllers\Student\KertasakaController::class, 'index'])->name('island.kertasaka');
        
        // 3. SUMARJA - Pulau CTF + Quest (popup)
        Route::prefix('sumarja')->name('sumarja.')->group(function () {
            Route::get('/', [App\Http\Controllers\Student\SumarjaController::class, 'index'])->name('index');
            Route::get('/island', [App\Http\Controllers\Student\SumarjaController::class, 'index'])->name('island');
            Route::get('/{challenge}', [App\Http\Controllers\Student\SumarjaController::class, 'show'])->name('show');
            Route::post('/{challenge}/submit', [App\Http\Controllers\Student\SumarjaController::class, 'submit'])->name('submit');
            Route::post('/quest/{quest}/complete', [App\Http\Controllers\Student\SumarjaController::class, 'complete'])->name('quest.complete');
        });
        Route::get('/island/sumarja', [App\Http\Controllers\Student\SumarjaController::class, 'index'])->name('island.sumarja');
        
        // 4. MARKASENA - Pulau Pengumuman + Profil
        Route::prefix('markasena')->name('markasena.')->group(function () {
            Route::get('/', [App\Http\Controllers\Student\MarkasenaController::class, 'index'])->name('index');
            Route::get('/island', [App\Http\Controllers\Student\MarkasenaController::class, 'index'])->name('island');
            Route::get('/profile', [App\Http\Controllers\Student\MarkasenaController::class, 'profile'])->name('profile');
            Route::put('/profile/update', [App\Http\Controllers\Student\MarkasenaController::class, 'updateProfile'])->name('profile.update');
            Route::put('/profile/password', [App\Http\Controllers\Student\MarkasenaController::class, 'updatePassword'])->name('profile.password');
            Route::post('/profile/logout', [App\Http\Controllers\Student\MarkasenaController::class, 'logout'])->name('profile.logout');
            Route::get('/{announcement}', [App\Http\Controllers\Student\MarkasenaController::class, 'show'])->name('show');
        });
        Route::get('/island/markasena', [App\Http\Controllers\Student\MarkasenaController::class, 'index'])->name('island.markasena');
        
        // ============ API ROUTES ============
        Route::prefix('api')->name('api.')->group(function () {
            // Pet API
            Route::get('/pet-data', [App\Http\Controllers\Student\MuaralayaController::class, 'getPetData'])->name('pet.data');
            Route::get('/pet-history', [App\Http\Controllers\Student\MuaralayaController::class, 'getFeedHistory'])->name('pet.history');
            Route::get('/pet-types', [App\Http\Controllers\Student\MuaralayaController::class, 'getPetTypes'])->name('pet.types');
            Route::post('/pet-change-type', [App\Http\Controllers\Student\MuaralayaController::class, 'changePetType'])->name('pet.change-type');
            Route::post('/pet-feed', [App\Http\Controllers\Student\MuaralayaController::class, 'feed'])->name('pet.feed'); // 🔥 TAMBAHKAN INI
        });
    });
});

// ============= REDIRECT DASHBOARD =============
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    $role = auth()->user()->role->name;
    return match($role) {
        'developer' => redirect()->route('developer.dashboard'),
        'mentor' => redirect()->route('mentor.dashboard'),
        'mahasiswa' => redirect()->route('student.hero'),
        default => redirect('/'),
    };
})->name('dashboard');