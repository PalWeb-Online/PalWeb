<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDialogRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:50'],
            'description' => ['nullable', 'max:500'],
            'sentences.*.speaker' => ['required'],
            'sentences.*.position' => ['required', 'integer'],
        ];
    }
}
