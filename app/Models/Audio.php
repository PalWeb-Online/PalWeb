<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Audio extends Model
{
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
}
