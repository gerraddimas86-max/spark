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
     * Sesuai dengan struktur folder: images/pets/{folder}/{stage}/{folder}.png
     */
    public function getImageAttribute(): string
    {
        // Mapping tipe pet ke nama folder
        $typeMap = [
            'octopus' => 'gurita',
            'ghost' => 'hantu',
            'parrot' => 'burung-beo',
            'shark' => 'hiu',
            'pufferfish' => 'ikan-buntal',
            'crab' => 'kepiting',
            'seahorse' => 'kuda-laut',
            'turtle' => 'kura-kura',
        ];
        
        // Mapping stage ke folder
        $stageMap = [
            'egg' => 'telur',
            'baby' => 'bayi',
            'adult' => 'dewasa',
            'legendary' => 'legendary',
        ];
        
        $folder = $typeMap[$this->type] ?? 'gurita';
        $stage = $stageMap[$this->stage] ?? 'telur';
        $filename = $folder . '.png';
        
        return asset("images/pets/{$folder}/{$stage}/{$filename}");
    }
    
    /**
     * Get pet icon based on type
     * Menggunakan Font Awesome icons
     */
    public function getIconAttribute(): string
    {
        $iconMap = [
            'octopus' => '<i class="fas fa-octopus-deploy"></i>',
            'ghost' => '<i class="fas fa-ghost"></i>',
            'parrot' => '<i class="fas fa-crow"></i>',
            'shark' => '<i class="fas fa-shark"></i>',
            'pufferfish' => '<i class="fas fa-fish"></i>',
            'crab' => '<i class="fas fa-crab"></i>',
            'seahorse' => '<i class="fas fa-horse-head"></i>',
            'turtle' => '<i class="fas fa-turtle"></i>',
        ];
        
        return $iconMap[$this->type] ?? '<i class="fas fa-paw"></i>';
    }
    
    /**
     * Get stage icon based on stage level
     * Menggunakan Font Awesome icons
     */
    public function getStageIconAttribute(): string
    {
        $stageIconMap = [
            'egg' => '<i class="fas fa-egg"></i>',
            'baby' => '<i class="fas fa-baby"></i>',
            'adult' => '<i class="fas fa-user-check"></i>',
            'legendary' => '<i class="fas fa-crown"></i>',
        ];
        
        return $stageIconMap[$this->stage] ?? '<i class="fas fa-question-circle"></i>';
    }
    
    /**
     * Get pet emoji based on type and stage (legacy, keep for compatibility)
     */
    public function getEmojiAttribute(): string
    {
        $emojiMap = [
            'ghost' => ['egg' => '🥚', 'baby' => '👻', 'adult' => '👻', 'legendary' => '👻👑'],
            'parrot' => ['egg' => '🥚', 'baby' => '🐣', 'adult' => '🦜', 'legendary' => '🦜👑'],
            'shark' => ['egg' => '🥚', 'baby' => '🦈', 'adult' => '🦈', 'legendary' => '🦈💎'],
            'octopus' => ['egg' => '🥚', 'baby' => '🐙', 'adult' => '🐙', 'legendary' => '🐙👑'],
            'pufferfish' => ['egg' => '🥚', 'baby' => '🐡', 'adult' => '🐡', 'legendary' => '🐡💎'],
            'crab' => ['egg' => '🥚', 'baby' => '🦀', 'adult' => '🦀', 'legendary' => '🦀💎'],
            'seahorse' => ['egg' => '🥚', 'baby' => '🐴', 'adult' => '🐴', 'legendary' => '🐴👑'],
            'turtle' => ['egg' => '🥚', 'baby' => '🐢', 'adult' => '🐢', 'legendary' => '🐢💎'],
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
     * Get stage name in Indonesian with icon
     */
    public function getStageNameAttribute(): string
    {
        $stageNameMap = [
            'egg' => 'Telur',
            'baby' => 'Bayi',
            'adult' => 'Dewasa',
            'legendary' => 'Legendaris',
        ];
        
        return $stageNameMap[$this->stage] ?? '🐾 Unknown';
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