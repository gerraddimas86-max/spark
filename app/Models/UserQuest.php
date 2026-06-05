<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuest extends Model
{
    protected $fillable = ['user_id', 'quest_id', 'is_completed', 'completed_date', 'quest_date'];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_date' => 'date',
        'quest_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class);
    }
}