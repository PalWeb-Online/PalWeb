<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'unit_id' => [
                'nullable',
                'integer',
                'exists:units,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $count = \App\Models\Lesson::where('unit_id', $value)->count();
                        if ($count >= 9) {
                            $fail('The selected Unit already has the maximum of 9 Lessons.');
                        }
                    }
                },
            ],
            'title' => ['required'],
            'skills.*.type' => ['required'],
            'skills.*.title' => ['required'],
            'skills.*.description' => ['required'],
        ];
    }
}
