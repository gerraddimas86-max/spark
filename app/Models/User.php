<?php

namespace App\Models;

// 1. TAMBAHKAN LINE INI
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // 2. TAMBAHKAN HasFactory DI SINI
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nim',        // TAMBAHKAN
        'password',
        'role_id',    // TAMBAHKAN
        'group_id',   // TAMBAHKAN
        'food_points', // TAMBAHKAN
        'is_active',  // TAMBAHKAN
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ===== TAMBAHKAN RELASI DI BAWAH INI =====

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function mentorGroups()
    {
        return $this->belongsToMany(Group::class, 'group_mentors', 'user_id', 'group_id');
    }

    public function userQuests()
    {
        return $this->hasMany(UserQuest::class);
    }

    public function cftAttempts()
    {
        return $this->hasMany(CftAttempt::class);
    }

    public function petFeedLogs()
    {
        return $this->hasMany(PetFeedLog::class);
    }

    // Helper cek role
    public function isDeveloper()
    {
        return $this->role && $this->role->name === 'developer';
    }

    public function isMentor()
    {
        return $this->role && $this->role->name === 'mentor';
    }

    public function isMahasiswa()
    {
        return $this->role && $this->role->name === 'mahasiswa';
    }

    // Login pakai NIM (untuk mahasiswa) atau email (untuk mentor/developer)
    public function findForPassport($username)
    {
        return $this->where('nim', $username)->orWhere('email', $username)->first();
    }
}
