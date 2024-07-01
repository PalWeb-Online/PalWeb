<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inflection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function terms()
    {
        return $this->belongsTo(Term::class);
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
