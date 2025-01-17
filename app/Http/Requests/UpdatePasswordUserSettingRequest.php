<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordUserSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'password_new' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ];
    }
}
