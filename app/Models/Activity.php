<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use App\Models\Traits\HasScoreStats;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[ScopedBy([PublishedScope::class])]
class Activity extends Model
{
    use HasFactory;
    use HasScoreStats;

    protected $fillable = [
        'title',
        'document',
        'published',
    ];

    protected function casts(): array
    {
        return [
            'document' => 'json',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Activity $activity) {
            $lesson = Lesson::where('activity_id', $activity->id)->first();

            if ($lesson && $lesson->published) {
                $lesson->update(['published' => false]);

                \Log::warning("Lesson {$lesson->slug} was automatically unpublished because its Activity was deleted.");
            }
        });
    }

    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class);
    }

    public function scores(): MorphMany
    {
        return $this->morphMany(Score::class, 'scorable')->orderByDesc('created_at');
    }
}
