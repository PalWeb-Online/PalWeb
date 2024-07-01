<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function dialect(): BelongsTo
    {
        return $this->belongsTo(Dialect::class, 'dialect_id');
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

    public function audify()
    {
        $find = [' ', '-'];
        $fix = ['_', ''];

        return str_replace($find, $fix, $this->translit);
    }
}
