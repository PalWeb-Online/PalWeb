<?php

namespace App\Models;

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

    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(Term::class)
            ->withPivot('gloss_id', 'sent_term', 'sent_translit', 'position')
            ->orderBy('position');
    }

    public function allTerms()
    {
        return DB::table('sentence_term')
            ->leftJoin('terms', 'sentence_term.term_id', '=', 'terms.id')
            ->where('sentence_term.sentence_id', $this->id)
            ->select('sentence_term.*', 'terms.*')
            ->orderBy('sentence_term.position')
            ->get();
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
}
