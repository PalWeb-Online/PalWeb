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

    protected $guarded = [
        'root'
    ];

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

    public function sentences(int $gloss_id = null): BelongsToMany
    {
        $relationship = $this->belongsToMany(Sentence::class)
            ->withPivot('gloss_id', 'sent_term', 'sent_translit', 'position');

        if ($gloss_id) {
            $relationship->wherePivot('gloss_id', $gloss_id);
        }

        return $relationship;
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
        return $this->relatives()->wherePivot('type', 'variant');
    }

    public function relatives(): BelongsToMany
    {
        return $this->belongsToMany(Term::class, 'term_relative', 'term_id', 'relative_id')
            ->withPivot('type');
    }

    public function references(): BelongsToMany
    {
        return $this->relatives()->wherePivot('type', 'reference');
    }

    public function components(): BelongsToMany
    {
        return $this->relatives()->wherePivot('type', 'component');
    }

    public function descendants(): BelongsToMany
    {
        return $this->relatives()->wherePivot('type', 'descendant');
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? false, function ($query, $search) use ($filters) {
            $isFullMatch = $filters['full_match'] ?? false;

            $query->where(function ($query) use ($search, $isFullMatch) {
                if ($isFullMatch) {
                    $query->where('term', $search)
                        ->orWhereHas('spellings', fn($query) => $query->where('spelling', $search))
                        ->orWhereHas('pronunciations', fn($query) => $query->where('translit', $search))
                        ->orWhereHas('inflections', fn($query) => $query->where('inflection', $search)
                            ->orWhere('translit', $search));

                } else {
                    $query->where('term', 'like', $search.'%')
                        ->orWhereHas('spellings', fn($query) => $query->where('spelling', 'like', $search.'%'))
                        ->orWhereHas('pronunciations', fn($query) => $query->where('translit', 'like', $search.'%'))
                        ->orWhereHas('inflections', fn($query) => $query->where('inflection', 'like', $search.'%')
                            ->orWhere('translit', 'like', $search.'%'));
                }
            });
        });

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
