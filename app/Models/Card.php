<?php

namespace App\Models;

use App\Enums\MasteryLevel;
use App\Services\CardDealer\ReviewOptions;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'term_id',
        'repetitions',
        'lapses',
        'step',
        'interval_days',
        'ease_factor',
        'due_at',
        'last_reviewed_at',
        'suspended_at',
    ];

    protected $casts = [
        'due_at' => 'date',
        'last_reviewed_at' => 'date',
        'suspended_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function cardReviews(): HasMany
    {
        return $this->hasMany(CardReview::class);
    }

    public function getMasteryScoreAttribute(): float
    {
        if ($this->repetitions === 0) {
            return 0;
        }

        $intervalWeight = log($this->interval_days + 1, 2);
        $repetitionWeight = min(1, $this->repetitions / 5);

        return min(1, ($intervalWeight * $repetitionWeight) / 6);
    }

    public function getMasteryLevelAttribute(): MasteryLevel
    {
        return MasteryLevel::fromScore($this->mastery_score, $this->repetitions === 0);
    }

    public function calculateNextInterval(int $grade, int $maxSteps): array
    {
        if ($grade === 0) {
            return ['days' => 0, 'label' => 'step 0'];
        }

        if ($this->step !== null) {
            if ($grade === 3) {
                return ['days' => 1, 'label' => '3d'];
            }

            $nextStep = ($grade === 1) ? $this->step : $this->step + 1;

            if ($nextStep >= $maxSteps) {
                return ['days' => 1, 'label' => '1d'];
            }

            return ['days' => 0, 'label' => "step $nextStep"];
        }

        $difficultyPenalty = max(0.5, 1 - ($this->lapses * 0.05));
        $overdueBonus = 1 + (max(0, today()->diffInDays($this->due_at)) / (max(1, $this->interval_days) * 2));

        $gradeBonus = match ($grade) {
            1 => 0.8,
            3 => 1.3,
            default => 1.0
        };

        $factor = $this->ease_factor * $gradeBonus * $overdueBonus * $difficultyPenalty;
        $days = (int) round($this->interval_days * $factor);

        $days = max($this->interval_days + 1, $days);

        return [
            'days' => $days,
            'label' => $days >= 30 ? round($days / 30).'mo' : $days.'d'
        ];
    }

    public function review(int $grade, int $secondsSpent, int $nextInterval, int $maxSteps): void
    {
        $cardState = $this->determineReviewType($grade, $maxSteps);

        $this->user->cardReviews()->create([
            'card_id' => $this->id,
            'type' => $cardState,
            'grade' => $grade,
            'seconds_spent' => $secondsSpent,
            'reviewed_at' => now()->toDateString(),
        ]);

        if ($this->step !== null) {
            if ($grade === 0) {
                $this->step = 0;
                $this->due_at = now();

            } elseif ($grade === 3) {
                $this->graduateFromSteps(true);

            } elseif ($grade === 2) {
                $this->step++;

                if ($this->step >= $maxSteps) {
                    $this->graduateFromSteps(false);

                } else {
                    $this->due_at = now();
                }
            }

        } else {
            if ($grade === 0) {
                $this->lapses++;
                $this->step = 0;
                $this->interval_days = 0;
                $this->due_at = now();
                $this->ease_factor = max(1.3, $this->ease_factor - 0.2);

            } else {
                $easeAdjustment = match ($grade) {
                    1 => -0.15,
                    3 => 0.15,
                    default => 0.0,
                };

                $this->ease_factor = max(1.3, min(3.0, $this->ease_factor + $easeAdjustment));

                $this->interval_days = $nextInterval;
                $this->due_at = now()->addDays($nextInterval);
                $this->repetitions++;
            }
        }

        $this->last_reviewed_at = now();
        $this->save();
    }

    protected function determineReviewType(int $grade, int $maxSteps): string
    {
        if ($this->repetitions === 0) {
            if ($grade === 3 || ($grade === 2 && ($this->step + 1) >= $maxSteps)) {
                return 'graduating';
            }

            return 'learning';
        }

        if ($this->step !== null) {
            return 'relearning';
        }

        return 'review';
    }

    protected function graduateFromSteps(bool $easyGraduation): void
    {
        $this->step = null;
        $this->repetitions = max(1, $this->repetitions + 1);

        if ($easyGraduation) {
            $this->interval_days = 3;
            $this->due_at = now()->addDays(3);
            $this->ease_factor = max(1.3, min(3.0, $this->ease_factor + 0.15));

        } else {
            $this->interval_days = 1;
            $this->due_at = now()->addDay();
        }
    }

    public static function createForUserAndTerm(User $user, Term $term): self
    {
        $card = self::create([
            'user_id' => $user->id,
            'term_id' => $term->id,
            'due_at' => Carbon::now(),
        ]);

        return $card->refresh()->load('term');
    }

    public function master(): void
    {
        $this->step = null;
        $this->repetitions = max(5, $this->repetitions + 1);
        $this->interval_days = 365;
        $this->ease_factor = max(2.5, $this->ease_factor);

        $this->suspend();
    }

    public function suspend(): void
    {
        $this->suspended_at = now();
        $this->due_at = null;
        $this->save();
    }

    public function restore(): void
    {
        $this->suspended_at = null;
        $this->due_at = now()->addDay();
        $this->save();
    }

    public function reset(): void
    {
        $this->forceFill([
            'repetitions' => 0,
            'lapses' => 0,
            'step' => 0,
            'interval_days' => 0,
            'ease_factor' => 2.5,
            'due_at' => now(),
            'last_reviewed_at' => null,
        ])->save();

        $this->cardReviews()->delete();
    }

    public static function purgeForUser(int $userId): int
    {
        return self::where('user_id', $userId)
            ->where('repetitions', 0)
            ->whereNotNull('step')
            ->delete();
    }

    public static function masteryScoreSql(): string
    {
        return "LEAST(1, (LOG2(interval_days + 1) * LEAST(1, repetitions / 5)) / 6)";
    }

    #[Scope]
    public function filterStatus($query, $status)
    {
        return match ($status) {
            'active' => $query->whereNull('suspended_at'),
            'suspended' => $query->whereNotNull('suspended_at'),
            default => $query,
        };
    }

    #[Scope]
    public function filterLevel($query, $level)
    {
        if ($level === null || $level === '') {
            return $query;
        }

        $rank = (int) $level;
        $scoreSql = self::masteryScoreSql();

        if ($rank === 0) {
            return $query->where('repetitions', 0);
        }

        $current = MasteryLevel::from($rank);
        $next = MasteryLevel::tryFrom($rank + 1);

        $query->whereRaw("$scoreSql >= ?", [$current->threshold()]);

        if ($next) {
            $query->whereRaw("$scoreSql < ?", [$next->threshold()]);
        }

        return $query;
    }

//    todo: new format doesn't work unless preceded by `query()`
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    #[Scope]
    public function forReviewOptions(Builder $query, ReviewOptions $options): Builder
    {
        return match ($options->scope) {
            'deck' => $query->whereHas('term.decks', fn ($q) => $q->where('decks.id', $options->deckId)),
            default => $query,
        };
    }

    #[Scope]
    public function inDeck(Builder $query, int $deckId): Builder
    {
        return $query->whereHas('term.decks', function ($q) use ($deckId) {
            $q->where('decks.id', $deckId);
        });
    }

    #[Scope]
    public function due(Builder $query): Builder
    {
        return $query->whereNotNull('due_at')
            ->whereDate('due_at', '<=', today());
    }

    #[Scope]
    public function owned(Builder $query): Builder
    {
        return $query->where('repetitions', '>', 0);
    }

    #[Scope]
    public function new(Builder $query): Builder
    {
        return $query->where('repetitions', 0);
    }

    #[Scope]
    public function suspended(Builder $query): Builder
    {
        return $query->whereNotNull('suspended_at');
    }

    #[Scope]
    public function active(Builder $query): Builder
    {
        return $query->whereNull('suspended_at');
    }

    #[Scope]
    public function sort(Builder $query, $sort): Builder
    {
        return match ($sort) {
            'due' => $query->orderByRaw('due_at IS NULL, due_at ASC'),
            'latest' => $query->orderByRaw('last_reviewed_at IS NULL, last_reviewed_at DESC'),
            'mastery' => $query->orderByRaw(self::masteryScoreSql()." DESC"),
            default => $query->latest(),
        };
    }
}
