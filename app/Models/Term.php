<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;

class Term extends Model
{
    use HasFactory;
    use Markable;

    protected static array $marks = [
        Bookmark::class,
    ];

    protected $guarded = ['root'];
    protected $casts = [
        'etymology' => 'array'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($term) {
            $term->bookmarks()->delete();
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

    // TODO: are the Featured In decks being ordered by the position of the term within it?
    public function decks(): BelongsToMany
    {
        return $this->belongsToMany(Deck::class)
            ->withPivot('position')
            ->orderBy('position');
    }

    public function root(): BelongsTo
    {
        return $this->belongsTo(Root::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
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

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relative', 'term_id', 'relative_id')
            ->wherePivot('type', 'variant');
    }

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relative', 'term_id', 'relative_id')
            ->wherePivot('type', 'reference');
    }

    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relative', 'term_id', 'relative_id')
            ->wherePivot('type', 'component');
    }

    public function descendants(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relative', 'term_id', 'relative_id')
            ->wherePivot('type', 'descendant');
    }

    /** Scopes */
    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? false,
            fn($query, $search) => $query->where(fn($query) => $query->where('term', 'like', $search.'%')
                ->orWhereHas('spellings', fn($query) => $query
                    ->where('spelling', 'like', $search.'%')
                )
                ->orWhereHas('pronunciations', fn($query) => $query
                    ->where('translit', 'like', $search.'%')
                )
                ->orWhereHas('inflections', fn($query) => $query
                    ->where('inflection', 'like', $search.'%')
                    ->orWhere('translit', 'like', $search.'%')
                )
                ->orWhereHas('glosses', function ($query) use ($search) {
                    $query
                        ->whereRaw("MATCH(gloss) AGAINST(? IN NATURAL LANGUAGE MODE)", [$search])
                        ->orWhere('gloss', 'LIKE', $search.'%');
                })
            )
        );
        $query->when($filters['category'] ?? false, fn($query, $category) => $query
            ->where('category', $category)
        );
        $query->when($filters['attribute'] ?? false, fn($query, $attribute) => $query
            ->whereHas('attributes', fn($query) => $query
                ->where('attribute', $attribute)
            )
        );
        $query->when($filters['form'] ?? false, fn($query, $form) => $query
            ->whereHas('patterns', fn($query) => $query
                ->where('form', $form)
            )
        );
        $query->when($filters['singular'] ?? false, fn($query, $singular) => $query
            ->whereHas('patterns', fn($query) => $query
                ->where('pattern', $singular)->where('type', 'singular')
            )
        );
        $query->when($filters['plural'] ?? false, fn($query, $plural) => $query
            ->whereHas('patterns', fn($query) => $query
                ->where('pattern', $plural)->where('type', 'plural')
            )
        );
    }
}
