<?php

namespace App\Http\Requests\Auth;

use App\Rules\ArabicScript;
use App\Rules\LatinScript;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRegisteredUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                new LatinScript,
            ],
            'ar_name' => [
                'required',
                'string',
                'max:50',
                new ArabicScript,
            ],
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9]+([._][a-zA-Z0-9]+)*$/',
                'unique:users',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}
