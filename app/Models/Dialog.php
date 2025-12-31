<?php

namespace App\Models;

use App\Models\Scopes\DialogScope;
use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ScopedBy([DialogScope::class])]
#[ScopedBy([PublishedScope::class])]
class Dialog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'media',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Dialog $dialog) {
            $lesson = Lesson::where('dialog_id', $dialog->id)->first();

            if ($lesson && $lesson->published) {
                $lesson->update(['published' => false]);

                \Log::warning("Lesson {$lesson->global_position} was automatically unpublished because its Dialog was deleted.");
            }
        });
    }

    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class);
    }

    public function sentences(): HasMany
    {
        return $this->hasMany(Sentence::class)->orderBy('position');
    }
}
