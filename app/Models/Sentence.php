<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Models\Scopes\PinnedScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;

#[ScopedBy([PinnedScope::class])]
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

    public function getAudio(): ?string
    {
        $dialect = auth()->user()?->dialect ?? Dialect::find(8);

        $dialectIds = $dialect->ancestors->sortDesc()->pluck('id')->prepend($dialect->id);

        return null;
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
