<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
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
        return Storage::disk('s3')->url('audios/'.$this->filename);
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function pronunciation(): BelongsTo
    {
        return $this->belongsTo(Pronunciation::class);
    }

    #[Scope]
    protected function orderByFluency(Builder $query, string $direction = 'desc'): Builder
    {
        return $query
            ->select('audios.*')
            ->join('speakers', 'speakers.id', '=', 'audios.speaker_id')
            ->orderBy('speakers.fluency', $direction)
            ->orderBy('audios.id', $direction);
    }

    #[Scope]
    protected function filter($query, array $filters): void
    {
        $query->when($filters['location'] ?? false, fn ($query, $location) => $query
            ->whereHas('speaker', fn ($query) => $query
                ->where('location_id', $location)
            )
        );

        $query->when($filters['dialect'] ?? false, fn ($query, $dialect) => $query
            ->whereHas('speaker', fn ($query) => $query
                ->where('dialect_id', $dialect)
            )
        );

        $query->when($filters['gender'] ?? false, fn ($query, $gender) => $query
            ->whereHas('speaker', fn ($query) => $query
                ->where('gender', $gender)
            )
        );
    }
}
