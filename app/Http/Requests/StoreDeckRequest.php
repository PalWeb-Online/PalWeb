<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeckRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'deck.name' => ['required', 'max:50'],
            'deck.description' => ['nullable', 'max:500'],
        ];
    }
}
