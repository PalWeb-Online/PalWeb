<?php

namespace App\Models;

use App\Models\Scopes\PinnedScope;
use App\Services\CardDealer\ReviewOptions;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as AttributeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;

#[ScopedBy([PinnedScope::class])]
class Term extends Model
{
    use HasFactory;
    use Markable;

    protected static array $marks = [
        Bookmark::class,
    ];

    protected $fillable = [
        'term',
        'translit',
        'root_id',
        'category',
        'slug',
        'etymology',
        'image',
        'usage',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($term) {
            $term->bookmarks()->delete();
        });
    }

    protected function casts(): array
    {
        return [
            'etymology' => 'array',
        ];
    }

    public function bookmarks(): MorphMany
    {
        return $this->morphMany(Bookmark::class, 'markable');
    }

    public function sentences(?int $gloss_id = null): BelongsToMany
    {
        $relationship = $this->belongsToMany(Sentence::class)
            ->withPivot('gloss_id', 'sent_term', 'sent_translit', 'position');

        if ($gloss_id) {
            $relationship->wherePivot('gloss_id', $gloss_id);
        }

        return $relationship;
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class)->where('user_id', auth()->id());
    }

    public function decks(): BelongsToMany
    {
        return $this->belongsToMany(Deck::class)
            ->withPivot('position');
    }

    public function root(): BelongsTo
    {
        return $this->belongsTo(Root::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function sortedTags(): AttributeCast
    {
        $priority = [
            'collective' => 1,
            'demonym' => 1,
            'defect' => 1,
            'pseudo' => 1,
            'masculine' => 2,
            'feminine' => 2,
            'plural' => 2,
            'idiom' => 3,
            'clitic' => 3,
        ];

        return AttributeCast::make(
            get: fn () => $this->attributes()->get()
                ->sortBy(fn ($attribute) => $priority[$attribute->attribute] ?? PHP_INT_MAX)
                ->values()
        );
    }

    public function patterns(): BelongsToMany
    {
        return $this->belongsToMany(Pattern::class);
    }

    public function glosses(): HasMany
    {
        return $this->hasMany(Gloss::class);
    }

    public function spellings(): HasMany
    {
        return $this->hasMany(Spelling::class);
    }

    public function inflections(): HasMany
    {
        return $this->hasMany(Inflection::class);
    }

    public function pronunciations(): HasMany
    {
        return $this->hasMany(Pronunciation::class);
    }

    public function relatives(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relative', 'term_id', 'relative_id')
            ->withPivot('type', 'gloss_id');
    }

    #[Scope]
    public function hasFluentAudio(Builder $query): Builder
    {
        return $query->whereHas('pronunciations.audios.speaker', function (Builder $query) {
            $query->where('fluency', '>=', 4);
        });
    }

    #[Scope]
    public function forReviewOptions(Builder $query, ReviewOptions $options, User $user): Builder
    {
        return match ($options->scope) {
            'deck' => $query->whereHas('decks', fn (Builder $deckQuery) => $deckQuery->whereKey($options->deckId)),
            'pinned' => $query->whereHas('decks', fn (Builder $deckQuery) => $deckQuery->whereHasBookmark($user)),
            'lesson' => $query->whereHas('decks.lesson', fn (Builder $lessonQuery) => $lessonQuery
                ->whereHas('users', fn (Builder $userQuery) => $userQuery
                    ->whereKey($user->id))),
            default => $query,
        };
    }

    #[Scope]
    public function withUserCard($query)
    {
//        this is currently redundant, but it may be better later to stop
//        scoping the default `cards()` relation to the auth user anyway
        return $query->with([
            'cards' => fn ($q) => $q
                ->where('user_id', auth()->id())
        ]);
    }

    #[Scope]
    public function withItemData($query)
    {
        return $query->with([
            'pronunciations.audios',
            'glosses'
        ]);
    }

    #[Scope]
    protected function match($query, ?string $search): void
    {
        $query->when($search, fn ($query) => $query
            ->where(fn ($query) => $query
                ->where('term', 'like', $search.'%')
                ->orWhereHas('spellings', fn ($query) => $query
                    ->where('spelling', 'like', $search.'%')
                )
                ->orWhereHas('pronunciations', fn ($query) => $query
                    ->where('translit', 'like', $search.'%')
                )
                ->orWhereHas('inflections', fn ($query) => $query
                    ->where('inflection', 'like', $search.'%')
                    ->orWhere('translit', 'like', $search.'%')
                )
            )
        );
    }

    #[Scope]
    protected function filter($query, array $filters): void
    {
        $query->when($filters['sort'] === 'alphabetical', fn ($query) => $query
            ->leftJoin('roots', 'terms.root_id', '=', 'roots.id')
            ->orderByRaw('IFNULL(roots.root, terms.term) ASC, roots.root IS NULL, terms.term ASC')
        );

        $query->when($filters['sort'] === 'frequency', fn ($query) => $query
            ->orderByDesc('usage_count')
        );

        $query->when($filters['sort'] === 'latest', fn ($query) => $query
            ->orderByDesc('terms.id')
        );

        $query->when($filters['pinned'] ?? false, fn ($query) => $query
            ->whereHasBookmark(auth()->user())
        );

        $query->when($filters['letter'] ?? false, fn ($query, $letter) => $query
            ->where(fn ($query) => $query
                ->whereHas('root', fn ($q) => $q->where('root', 'like', $letter.'%'))
                ->orWhere(fn ($q) => $q
                    ->whereDoesntHave('root')
                    ->where('term', 'like', $letter.'%')
                )
            )
        );

        $query->when($filters['category'] ?? false, fn ($query, $category) => $query
            ->where('category', $category)
        );

        $query->when($filters['attribute'] ?? false, fn ($query, $attribute) => $query
            ->whereHas('attributes', fn ($query) => $query->where('attribute', $attribute))
        );

        $query->when($filters['form'] ?? false, fn ($query, $form) => $query
            ->whereHas('patterns', fn ($query) => $query->where('form', $form))
        );

        $query->when($filters['singular'] ?? false, fn ($query, $singular) => $query
            ->whereHas('patterns', fn ($query) => $query->where('pattern', $singular)->where('type', 'singular'))
        );

        $query->when($filters['plural'] ?? false, fn ($query, $plural) => $query
            ->whereHas('patterns', fn ($query) => $query->where('pattern', $plural)->where('type', 'plural'))
        );
    }
}
