<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StashRecordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return ['file' => [
            'required',
            'file',
            'mimes:wav',
            'max:5120',
        ], ];
    }
}
