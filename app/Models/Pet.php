<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    protected $fillable = ['name', 'group_id', 'type', 'level', 'experience', 'stage', 'image_url'];

    protected $casts = [
        'level' => 'integer',
        'experience' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function feedLogs(): HasMany
    {
        return $this->hasMany(PetFeedLog::class);
    }
    
    /**
     * Get image URL based on pet type and stage
     */
    public function getImageAttribute(): string
    {
        $stage = $this->stage ?? 'egg';
        return asset("images/pets/{$this->type}_{$stage}.png");
    }
    
    /**
     * Get pet emoji based on type and stage
     */
    public function getEmojiAttribute(): string
    {
        $emojiMap = [
            'ghost' => ['egg' => '🥚', 'baby' => '👻', 'adult' => '👻', 'legendary' => '👻👑'],
            'parrot' => ['egg' => '🥚', 'baby' => '🐣', 'adult' => '🦜', 'legendary' => '🦜👑'],
            'shark' => ['egg' => '🥚', 'baby' => '🦈', 'adult' => '🦈', 'legendary' => '🦈💎'],
            'octopus' => ['egg' => '🥚', 'baby' => '🐙', 'adult' => '🐙', 'legendary' => '🐙👑'],
            'dragon' => ['egg' => '🥚', 'baby' => '🐉', 'adult' => '🐉', 'legendary' => '🐉🔥👑'],
            'phoenix' => ['egg' => '🥚', 'baby' => '🔥', 'adult' => '🦅', 'legendary' => '🦅🔥👑'],
            'turtle' => ['egg' => '🥚', 'baby' => '🐢', 'adult' => '🐢', 'legendary' => '🐢💎'],
            'whale' => ['egg' => '🥚', 'baby' => '🐋', 'adult' => '🐋', 'legendary' => '🐋👑'],
        ];
        
        return $emojiMap[$this->type][$this->stage] ?? '🐾';
    }
    
    /**
     * Update stage based on current level
     * Rule:
     * - Level 0: egg
     * - Level 1-4: baby
     * - Level 5-9: adult
     * - Level 10+: legendary
     */
    public function updateStage(): bool
    {
        $oldStage = $this->stage;
        $newStage = $this->stage;
        
        if ($this->level == 0) {
            $newStage = 'egg';
        } elseif ($this->level >= 1 && $this->level <= 4) {
            $newStage = 'baby';
        } elseif ($this->level >= 5 && $this->level <= 9) {
            $newStage = 'adult';
        } elseif ($this->level >= 10) {
            $newStage = 'legendary';
        }
        
        if ($oldStage !== $newStage) {
            $this->stage = $newStage;
            $this->save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Get stage name in Indonesian
     */
    public function getStageNameAttribute(): string
    {
        return match($this->stage) {
            'egg' => '🥚 Telur',
            'baby' => '🐣 Bayi',
            'adult' => '🦅 Dewasa',
            'legendary' => '👑 Legendaris',
            default => '🐾 Unknown',
        };
    }
    
    /**
     * Get stage badge color
     */
    public function getStageBadgeColorAttribute(): string
    {
        return match($this->stage) {
            'egg' => 'bg-gray-600',
            'baby' => 'bg-blue-600',
            'adult' => 'bg-purple-600',
            'legendary' => 'bg-yellow-600',
            default => 'bg-gray-500',
        };
    }
}