<?php

namespace App\Models;

use App\Models\Scopes\DeckScope;
use App\Models\Traits\HasScoreStats;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;

#[ScopedBy([DeckScope::class])]
class Deck extends Model
{
    use HasFactory;
    use HasScoreStats;
    use Markable;

    protected static array $marks = [
        Bookmark::class,
    ];

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'private',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($deck) {
            $deck->bookmarks()->delete();
        });
    }

    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'markable');
    }

    public function isPinned(): bool
    {
        $user = auth()->user();
        if ($user) {
            return Bookmark::has($this, $user);
        } else {
            return false;
        }
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class)
            ->withPivot('gloss_id', 'position')
            ->orderBy('position');
    }

    public function scores(): MorphMany
    {
        return $this->morphMany(Score::class, 'scorable')->orderByDesc('created_at');
    }

    #[Scope]
    protected function filter($query, array $filters): void
    {
        $query->when($filters['sort'] === 'popular', fn ($query) => $query
            ->leftJoin('markable_bookmarks', function ($join) {
                $join->on('markable_bookmarks.markable_id', '=', 'decks.id')
                    ->where('markable_bookmarks.markable_type', '=', self::class);
            })
            ->selectRaw('decks.*, COUNT(markable_bookmarks.id) as pins_count')
            ->groupBy('decks.id')
            ->orderByDesc('pins_count')
        );

        $query->when($filters['sort'] === 'latest', fn ($query) => $query
            ->orderByDesc('decks.id')
        );

        $query->when($filters['pinned'] ?? false, fn ($query) => $query
            ->whereHasBookmark(auth()->user())
        );
    }
}
