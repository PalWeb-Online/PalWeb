<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;

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

    public function isPinned(): bool
    {
        $user = auth()->user();
        if ($user) {
            return Bookmark::has($this, $user);
        } else {
            return false;
        }
    }

    public function getUserPronunciationData(): array
    {
        $dialect = auth()->user()?->dialect ?? Dialect::find(8);
        $dialectIds = $dialect->ancestors->sortDesc()->pluck('id')->prepend($dialect->id);

        $userPronunciation = $this->pronunciations()
            ->whereIn('dialect_id', $dialectIds)
            ->with([
                'audios' => fn ($query) => $query
                    ->limit(1)
                    ->with(['speaker.user'])
            ])
            ->limit(1)
            ->first();

        if ($userPronunciation) {
            $pronunciations = collect([$userPronunciation]);

        } else {
            $pronunciations = collect([
                $this->pronunciations()
                    ->with([
                        'audios' => fn ($query) => $query
                            ->limit(1)
                            ->with(['speaker.user'])
                    ])
                    ->limit(1)
                    ->first()
            ]);
        }

        return [
            'audio' => $pronunciations->first()->audios?->first()?->filename,
            'translit' => $pronunciations->first()->translit,
            'pronunciations' => $pronunciations,
        ];
    }

    public function getSingleGlossSentence(): Collection
    {
        $sentenceData = DB::table('sentence_term')
            ->where('sentence_term.term_id', $this->id)
            ->groupBy('gloss_id')
            ->selectRaw('gloss_id, MIN(sentence_term.sentence_id) AS sentence_id, COUNT(*) as sentences_count')
            ->get();

        $sentenceCounts = collect();
        $sentenceIds = collect();

        $sentenceData->each(function ($row) use ($sentenceCounts, $sentenceIds) {
            $sentenceIds[$row->gloss_id] = $row->sentence_id;
            $sentenceCounts[$row->gloss_id] = $row->sentences_count;
        });

        $sentences = Sentence::whereIn('id', $sentenceIds->values())->get();

        return $sentences->groupBy(function ($sentence) use ($sentenceIds) {
            return $sentenceIds->flip()[$sentence->id];
        })->map(function ($group, $glossId) use ($sentenceCounts) {
            return [
                'sentences' => $group,
                'sentences_count' => $sentenceCounts[$glossId],
            ];
        });
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

    public function getSortedAttributesAttribute(): Collection
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

        return $this->attributes()->get()->sortBy(function ($attribute) use ($priority) {
            return $priority[$attribute->attribute] ?? PHP_INT_MAX;
        })->values();
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
            ->withPivot('type');
    }

    public function scopeMatch($query, array $filters): void
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('term', 'like', $search.'%')
                    ->orWhereHas('spellings', fn ($query) => $query
                        ->where('spelling', 'like', $search.'%')
                    )
                    ->orWhereHas('pronunciations', fn ($query) => $query
                        ->where('translit', 'like', $search.'%')
                    )
                    ->orWhereHas('inflections', fn ($query) => $query
                        ->where('inflection', 'like', $search.'%')
                        ->orWhere('translit', 'like', $search.'%')
                    );
            });
        });
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['pinned'] ?? false, fn ($query) => $query
            ->whereHasBookmark(auth()->user())
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

        $query->when($filters['letter'] ?? false, fn ($query, $letter) => $query
            ->where(fn ($query) => $query
                ->whereHas('root', fn ($q) => $q->where('root', 'like', $letter.'%'))
                ->orWhere(fn ($q) => $q
                    ->whereDoesntHave('root')
                    ->where('term', 'like', $letter.'%')
                )
            )
        );
    }
}
