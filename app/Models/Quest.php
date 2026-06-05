<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quest extends Model
{
    protected $fillable = ['title', 'description', 'type', 'food_reward', 'is_daily'];

    public function userQuests(): HasMany
    {
        return $this->hasMany(UserQuest::class);
    }
}