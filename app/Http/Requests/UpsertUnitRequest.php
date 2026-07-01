<?php

namespace App\Http\Requests;

use App\Support\Blocks\BlockValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpsertUnitRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'lessons.*.title' => ['required', 'string'],
        ];
    }
}
