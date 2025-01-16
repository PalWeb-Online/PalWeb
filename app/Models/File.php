<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static function getDefaultStoragePath(): string
    {
        return 'files/';
    }

    protected $fillable = [
        'uuid',
        'filename',
        'path',
        'size',
        'mime_type',
        'extension',
        'label',
        'fileable_type',
        'fileable_id',
        'is_public',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
    ];

    protected $appends = [
        'public_url',
    ];

    public function getPublicUrlAttribute()
    {
        return Storage::disk('s3')->url($this->path.'/'.$this->filename);
    }

    public function isPublic(): bool
    {
        return $this->is_public;
    }

    public function fileable()
    {
        return $this->morph();
    }

    public function getKeyName()
    {
        return 'uuid';
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
