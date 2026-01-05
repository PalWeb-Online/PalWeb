<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'scorable_type' => ['required', 'string', Rule::in(array_keys(Relation::morphMap()))],
            'scorable_id' => ['required', 'integer'],
            'settings' => ['present', 'array'],
            'score' => ['required', 'numeric', 'min:0', 'max:1'],
            'results' => ['required', 'array'],
        ];
    }
}
