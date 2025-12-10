<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Http\Resources\TermResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;

class Sentence extends Model
{
    use HasFactory;
    use Markable;

    protected static array $marks = [
        Bookmark::class,
    ];

    protected $fillable = [
        'sentence',
        'translit',
        'trans',
        'dialog_id',
        'speaker',
        'position',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($sentence) {
            $sentence->bookmarks()->delete();
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

    public function getAudio(): ?string
    {
        $dialect = auth()->user()?->dialect ?? Dialect::find(8);

        $dialectIds = $dialect->ancestors->sortDesc()->pluck('id')->prepend($dialect->id);

        return null;
    }

    public function getTerms(): array
    {
        $allTerms = DB::table('sentence_term')
            ->leftJoin('terms', 'sentence_term.term_id', '=', 'terms.id')
            ->where('sentence_term.sentence_id', $this->id)
            ->select('sentence_term.*', 'terms.*')
            ->orderBy('sentence_term.position')
            ->get();

        return $allTerms->map(function ($sentenceTerm) {
            if ($sentenceTerm->id) {
                $term = Term::find($sentenceTerm->id);
                $term->load(['pronunciations']);

                $termResource = new TermResource($term)->toArray(request());
                $termResource['sentencePivot'] = [
                    'gloss_id' => $sentenceTerm->gloss_id,
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                ];

                return $termResource;
            }

            return [
                'sentencePivot' => [
                    'sent_term' => $sentenceTerm->sent_term,
                    'sent_translit' => $sentenceTerm->sent_translit,
                    'position' => $sentenceTerm->position,
                ],
            ];
        })->values()->toArray();
    }

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class)
            ->withPivot('gloss_id', 'sent_term', 'sent_translit', 'position')
            ->orderBy('position');
    }

    public function dialog(): BelongsTo
    {
        return $this->belongsTo(Dialog::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function sound()
    {
        if ($this->file) {
            return $this->file->getPublicUrlAttribute();
        }

        return null;
    }

    #[Scope]
    protected function filter($query, array $filters): void
    {
        $query->when($filters['sort'] === 'latest', fn ($query) => $query
            ->orderByDesc('sentences.id')
        );

        $query->when($filters['pinned'] ?? false, fn ($query) => $query
            ->whereHasBookmark(auth()->user())
        );
    }
}
