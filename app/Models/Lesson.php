<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([PublishedScope::class])]
class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'unit_id',
        'position',
        'slug',
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

    public function isUnlockedFor(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    public function getProgressFor(User $user): array
    {
        $deck = $this->deck;
        $activity = $this->activity;

        $deckScores = $deck
            ? $deck->scores()
                ->where('user_id', $user->id)
                ->where('score', 1)
                ->count()
            : 0;

        $activityScores = $activity
            ? $activity->scores()
                ->where('user_id', $user->id)
                ->where('score', 1)
                ->count()
            : 0;

        if (auth()->user()->isAdmin()) {
            return [
                'scores_count' => [
                    'deck' => 3,
                    'activity' => 1,
                ],
                'unlocked' => true,
                'stage' => 3,
                'completed' => true,
            ];

        } else {
            return [
                'scores_count' => [
                    'deck' => $deckScores,
                    'activity' => $activityScores,
                ],
                'unlocked' => $this->isUnlockedFor($user),
                'stage' => $this->users()->where('user_id', $user->id)->first()?->pivot?->stage,
                'completed' => $this->users()->where('user_id', $user->id)->first()?->pivot?->completed,
            ];
        }
    }
}
