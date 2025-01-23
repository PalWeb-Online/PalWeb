<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSentenceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sentence.trans' => ['required'],
            'sentence.terms' => ['required', 'array'],
            'sentence.terms.*.pivot.sent_term' => ['required'],
            'sentence.terms.*.pivot.sent_translit' => ['required'],
        ];
    }
}
