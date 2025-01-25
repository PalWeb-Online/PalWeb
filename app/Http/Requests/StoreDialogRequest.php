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
            'dialog.title' => ['required', 'max:50'],
            'dialog.description' => ['nullable', 'max:500'],
            'dialog.sentences' => ['required', 'array'],
            'dialog.sentences.*.speaker' => ['required'],
            'dialog.sentences.*.position' => ['required', 'integer'],
        ];
    }
}
