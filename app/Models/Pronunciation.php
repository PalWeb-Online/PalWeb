<?php

namespace App\Models;

use App\Models\Scopes\PronunciationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pronunciation extends Model
{
    use HasFactory;

    protected $fillable = [
        'term_id',
        'translit',
        'phonemic',
        'phonetic',
        'borrowed',
        'dialect_id',
    ];

    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new PronunciationScope);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function dialect(): BelongsTo
    {
        return $this->belongsTo(Dialect::class, 'dialect_id');
    }

    public function audios(): hasMany
    {
        return $this->hasMany(Audio::class)
            ->with('speaker')
            ->orderByFluency();
    }

    public function file()
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
