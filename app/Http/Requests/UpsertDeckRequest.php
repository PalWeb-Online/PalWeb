<?php

namespace App\Http\Requests;

use App\Models\Gloss;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpsertDeckRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:50'],
            'description' => ['nullable', 'max:500'],
            'private' => ['boolean'],
            'terms' => ['array'],
            'terms.*.id' => ['required', 'integer', 'exists:terms,id'],
            'terms.*.deckPivot.gloss_id' => ['required', 'integer', 'exists:glosses,id'],
            'terms.*.deckPivot.position' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $seen = [];

                foreach ($this->input('terms', []) as $index => $term) {
                    $termId = data_get($term, 'id');
                    $glossId = data_get($term, 'deckPivot.gloss_id');

                    if (! $termId || ! $glossId) {
                        continue;
                    }

                    $belongsToTerm = Gloss::query()
                        ->whereKey($glossId)
                        ->where('term_id', $termId)
                        ->exists();

                    if (! $belongsToTerm) {
                        $validator->errors()->add(
                            "terms.$index.deckPivot.gloss_id",
                            'The selected Gloss does not belong to this Term.'
                        );

                        continue;
                    }

                    $key = "$termId:$glossId";

                    if (isset($seen[$key])) {
                        $validator->errors()->add(
                            "terms.$index.deckPivot.gloss_id",
                            'This Term is already in the Deck with the selected Gloss.'
                        );
                    }

                    $seen[$key] = true;
                }
            },
        ];
    }
}
