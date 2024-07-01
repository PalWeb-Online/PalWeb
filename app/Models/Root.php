<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Root extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    const arabic = [
        'ب',
        'ت',
        'ث',
        'ج',
        'ح',
        'خ',
        'د',
        'ذ',
        'ر',
        'ز',
        'س',
        'ش',
        'ص',
        'ض',
        'ط',
        'ظ',
        'ع',
        'غ',
        'ف',
        'ق',
        'ك',
        'ل',
        'م',
        'ن',
        'ه',
        'و',
        'ي',
        'ء',
    ];

    const translit = [
        'b',
        't',
        'ŧ',
        'ž',
        'ħ',
        'x',
        'd',
        'ð',
        'r',
        'z',
        's',
        'š',
        'ṣ',
        'ḍ',
        'ṭ',
        'ẓ',
        'ʕ',
        'ġ',
        'f',
        'q',
        'k',
        'l',
        'm',
        'n',
        'h',
        'w',
        'y',
        'ʔ',
    ];

    public function rootArray()
    {
        $root = mb_str_split($this->root);
        $rootTr = str_replace(Root::arabic, Root::translit, $root);

        // TODO: Only apply to conjugation charts, not Root Box.
        if (in_array('ء', $root)) {
            $root = str_replace('ء', 'أ', $root);
        }

        return [$root, $rootTr];
    }

    public function showRoot()
    {
        $root = implode(' ', Root::rootArray()[0]);
        $root = str_replace('أ', 'ء', $root);

        return $root;
    }

    public function transRoot()
    {
        $rootTr = implode(' ', Root::rootArray()[1]);

        return $rootTr;
    }
}
