<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    protected $fillable = ['name', 'group_id', 'level', 'experience', 'image_url'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function feedLogs(): HasMany
    {
        return $this->hasMany(PetFeedLog::class);
    }
}