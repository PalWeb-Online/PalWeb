<?php

namespace App\Http\Requests;

use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'term.term' => ['required', new ArabicScript],
            'root' => ['nullable', 'min:3', 'max:4', new ArabicScript],
            'inflections.*.inflection' => ['required', new ArabicScript],
            'inflections.*.translit' => ['required', new LatinScript],
            'spellings.*.spelling' => ['required', new ArabicScript],
            'variants.*.slug' => ['required', new LatinScript],
            'references.*.slug' => ['required', new LatinScript],
            'components.*.slug' => ['required', new LatinScript],
            'descendants.*.slug' => ['required', new LatinScript],
            'glosses.*.relatives.*.slug' => ['required', new LatinScript],
        ];
    }
}
