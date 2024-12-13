<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Audio extends Model
{
    use HasFactory;

    protected $table = 'audios';

    protected $fillable = [
        'filename',
        'speaker_id',
        'pronunciation_id',
    ];

    public function url()
    {
        return Storage::disk('s3')->url('/audio/'.$this->filename);
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function pronunciation(): BelongsTo
    {
        return $this->belongsTo(Pronunciation::class);
    }

    public function scopeOrderByFluency(Builder $query, string $direction = 'desc'): Builder
    {
        return $query
            ->select('audios.*')
            ->join('speakers', 'speakers.id', '=', 'audios.speaker_id')
            ->orderBy('speakers.fluency', $direction)
            ->orderBy('audios.id', $direction);
    }
}
