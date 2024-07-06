<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;

class Sentence extends Model
{
    use Markable;

    protected static array $marks = [
        Bookmark::class,
    ];

    protected $guarded = [];

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

    public function glosses(): BelongsToMany
    {
        return $this->belongsToMany(Gloss::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    /**
     * Retrieves the terms from the given sentence.
     *
     * @param  string|null  $currentTerm  The current term.
     * @return \Illuminate\Support\Collection The collection of sentence terms.
     */
    public function getTerms($currentTerm = null): Collection
    {
        $sentenceTerms = explode(",", $this->sentence);
        $sentenceTermsCollection = collect([]);

        foreach ($sentenceTerms as $sentenceTerm) {
            $sentenceTerm = trim($sentenceTerm, "/");
            $sentenceTerm = explode("/", $sentenceTerm);

            $foundTerm = Term::firstWhere('slug', $sentenceTerm[0]);

            if ($foundTerm) {
                $foundTerm->sent_term = $sentenceTerm[2];
                $foundTerm->sent_translit = $sentenceTerm[1];
                if ($currentTerm) {
                    $foundTerm->current = $currentTerm == $sentenceTerm[0];
                }
                $sentenceTermsCollection->push($foundTerm);
            } else {
                $sentenceTermsCollection->push([
                    'sent_term' => $sentenceTerm[2],
                    'sent_translit' => $sentenceTerm[1],
                ]);
            }
        }

        return $sentenceTermsCollection;
    }

    public function sound()
    {
        if ($this->file) {
            return $this->file->getPublicUrlAttribute();
        }
        return null;
    }

    /**
     * Replace specific characters in the given string with other characters to audify it.
     *
     * @return string The audified string.
     */
    public function audify()
    {
        $find = array(' ', '-');
        $fix = array('_', '');
        return str_replace($find, $fix, $this->translit);
    }
}
