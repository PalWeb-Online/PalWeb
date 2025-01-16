<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Root extends Model
{
    use HasFactory;

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
        'j',
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
        'ż',
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

    const translitOverrides = [
        '1' => [],
        '4' => [],
        '5' => [],
        '7' => [],

        '2' => [
            'ق' => 'ʔ',
            'ث' => 't',
            'ذ' => 'z',
            'ظ' => 'ẓ',
            'ج' => 'ž',
        ],
        '8' => [
            'ق' => 'ʔ',
            'ث' => 't',
            'ذ' => 'z',
            'ظ' => 'ẓ',
            'ج' => 'ž',
        ],
        '9' => [
            'ق' => 'ʔ',
            'ث' => 't',
            'ذ' => 'z',
            'ظ' => 'ẓ',
            'ج' => 'ž',
        ],

        '3' => [
            'ك' => 'č',
            'ق' => 'k',
        ],
        '10' => [
            'ك' => 'č',
            'ق' => 'k',
        ],
        '11' => [
            'ك' => 'č',
            'ق' => 'k',
        ],

        '6' => ['ق' => 'g'],
    ];

    protected $guarded = [];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function generateRoot(?Term $term = null): array
    {
        $arabic = mb_str_split($this->root);
        $translit = str_replace(self::arabic, self::translit, $arabic);

        if ($term) {
            $arabic = str_replace('ء', 'أ', $arabic);
            $translits = [];

            foreach ($term->pronunciations as $pronunciation) {
                $dialect = $pronunciation->dialect;
                $dialectTranslit = $this->generateTranslits($translit, $dialect);

                $translits[] = [
                    'dialect' => $dialect->name,
                    'translit' => $dialectTranslit,
                ];
            }

            return [$arabic, $translits];

        } else {
            $arabic = implode(' ', $arabic);
            $translit = implode(' ', $translit);

            return [$arabic, $translit];
        }
    }

    private function generateTranslits(array $translit, $dialect): array|string
    {

        if (isset(self::translitOverrides[$dialect->id])) {
            $overrides = self::translitOverrides[$dialect->id];

            foreach ($overrides as $arabicLetter => $dialectTranslit) {
                $defaultTranslit = str_replace(self::arabic, self::translit, $arabicLetter);

                $translit = str_replace(
                    $defaultTranslit,
                    $dialectTranslit,
                    $translit
                );
            }
        }

        return $translit;
    }
}
