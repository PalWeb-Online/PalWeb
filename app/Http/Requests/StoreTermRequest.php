<?php

namespace App\Http\Requests;

use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Illuminate\Foundation\Http\FormRequest;

class StoreTermRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'term' => ['required', new ArabicScript],
            'category' => ['required'],
            'pronunciations.*.translit' => ['required'],
            'pronunciations.*.phonemic' => ['required'],
            'pronunciations.*.phonetic' => ['required'],
            'pronunciations.*.dialect_id' => ['required'],
            'root.root' => ['nullable', 'min:3', 'max:4', new ArabicScript],
            'etymology.type' => ['required'],
            'attributes.*.attribute' => ['required'],
            'spellings.*.spelling' => ['required', new ArabicScript],
            'relatives.*.slug' => ['required', new LatinScript],
            'relatives.*.type' => ['required'],
            'glosses.*.gloss' => ['required'],
            'glosses.*.attributes.*.attribute' => ['required'],
            'glosses.*.relatives.*.slug' => ['required', new LatinScript],
            'glosses.*.relatives.*.type' => ['required'],
            'inflections.*.form' => ['required'],
            'inflections.*.inflection' => ['required', new ArabicScript],
            'inflections.*.translit' => ['required', new LatinScript],
        ];
    }
}
