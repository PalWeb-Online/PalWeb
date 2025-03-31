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
            'trans' => ['required'],
            'terms' => ['required', 'array'],
            'terms.*.sentencePivot.sent_term' => ['required'],
            'terms.*.sentencePivot.sent_translit' => ['required'],
        ];
    }
}
