<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CftChallenge extends Model
{
    protected $fillable = ['title', 'description', 'flag', 'food_reward', 'points', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function attempts(): HasMany
    {
        return $this->hasMany(CftAttempt::class);
    }
}