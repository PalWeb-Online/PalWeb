<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Avatar extends Model
{
    private const string STORAGE_PATH = 'images/avatars';

    protected $fillable = [
        'user_id',
        'filename',
    ];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk('s3')->url(self::STORAGE_PATH.'/'.$this->filename),
        );
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
