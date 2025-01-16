<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StashRecordRecordWizardRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['file' => 'required|file|mimes:wav|max:5120'];
    }
}
