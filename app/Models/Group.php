<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $fillable = ['name', 'code', 'pet_health'];

    public function mentors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_mentors', 'group_id', 'user_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(User::class)->where('role_id', 3); // role_id 3 = mahasiswa
    }

    public function pet(): HasOne
    {
        return $this->hasOne(Pet::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }
}