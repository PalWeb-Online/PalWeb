<?php

namespace App\Http\Requests;

use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTermRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'term.term' => ['required', new ArabicScript],
            'term.category' => ['required'],
            'term.pronunciations.*.translit' => ['required'],
            'term.pronunciations.*.phonemic' => ['required'],
            'term.pronunciations.*.phonetic' => ['required'],
            'term.pronunciations.*.dialect.id' => ['required'],
            'term.root.root' => ['nullable', 'min:3', 'max:4', new ArabicScript],
            'term.etymology.type' => ['required'],
            'term.attributes.*.attribute' => ['required'],
            'term.spellings.*.spelling' => ['required', new ArabicScript],
            'term.relatives.*.slug' => ['required', new LatinScript],
            'term.relatives.*.type' => ['required'],
            'term.glosses.*.gloss' => ['required'],
            'term.glosses.*.attributes.*.attribute' => ['required'],
            'term.glosses.*.relatives.*.slug' => ['required', new LatinScript],
            'term.glosses.*.relatives.*.type' => ['required'],
            'term.inflections.*.form' => ['required'],
            'term.inflections.*.inflection' => ['required', new ArabicScript],
            'term.inflections.*.translit' => ['required', new LatinScript],
        ];
    }
}
