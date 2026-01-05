<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use App\Observers\LessonObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([LessonObserver::class])]
#[ScopedBy([PublishedScope::class])]
class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'unit_id',
        'unit_position',
        'global_position',
        'title',
        'description',
        'document',
        'deck_id',
        'activity_id',
        'dialog_id',
        'unlock_conditions',
        'published',
    ];

    protected $casts = [
        'document' => 'json',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function dialog(): BelongsTo
    {
        return $this->belongsTo(Dialog::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['stage', 'completed'])
            ->withTimestamps();
    }

    public function getProgressFor(?User $user): array
    {
        if (!$user) return ['unlocked' => false, 'stage' => 0, 'completed' => false];

        $scores = $user->getScoreCounts();

        $unlocked = $user->hasUnlockedLesson($this);
        $stage = $user->getLessonStage($this);
        $completed = $user->hasCompletedLesson($this);

        return [
            'scores_count' => [
                'deck' => $scores["deck:{$this->deck_id}"] ?? 0,
                'activity' => $scores["activity:{$this->activity_id}"] ?? 0,
            ],
            'unlocked' => $unlocked,
            'stage' => $stage,
            'completed' => $completed,
        ];
    }
}
