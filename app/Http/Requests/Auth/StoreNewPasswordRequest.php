<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreNewPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => [
                'required',
                'email',
            ],
            'password_new' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}
