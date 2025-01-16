<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
'token'        => ['required'],
'email'        => [
'required',
'email',
],
'password_new' => [
'required',
'confirmed',
Rules\Password::defaults(),
],
];
    }
}
