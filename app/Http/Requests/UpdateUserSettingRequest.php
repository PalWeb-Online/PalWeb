<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
'name'     => [
'required',
'string',
'max:50',
new LatinScript(),
],
'ar_name'  => [
'required',
'string',
'max:50',
new ArabicScript(),
],
'username' => [
'required',
'string',
'max:50',
'regex:/^[a-zA-Z0-9]+([._][a-zA-Z0-9]+)*$/',
Rule::unique('users')->ignore($user->id),
],
'home'     => [
'nullable',
'string',
'max:100',
],
'bio'      => [
'nullable',
'string',
'max:500',
],
];
    }
}
