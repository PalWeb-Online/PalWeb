<?php

namespace App\Http\Requests;

use App\Models\Lesson;
use App\Support\Blocks\BlockValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpsertLessonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'group' => ['required', 'string', 'in:main,extra'],
            'unit_id' => [
                'nullable',
                'integer',
                'exists:units,id',
                function ($attribute, $value, $fail) {
                    if (! $value) {
                        return;
                    }

                    $lesson = $this->route('lesson');

                    if (! $lesson || $lesson->unit_id != $value) {
                        if (Lesson::where('unit_id', $value)->count() >= 9) {
                            $fail('The selected Unit already has the maximum of 9 Lessons.');
                        }
                    }
                },
            ],
            'title' => ['required'],
            'deck_id' => ['nullable', 'integer', 'exists:decks,id'],
            'dialog_id' => ['nullable', 'integer', 'exists:dialogs,id'],
            'document' => ['required', 'array'],
            'document.schemaVersion' => ['required', 'integer'],
            'document.skills' => ['present', 'array'],
            'published' => ['required', 'boolean'],
            'unlock_conditions' => ['nullable', 'array'],
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->input('group') === 'main') {
            $this->merge(['unlock_conditions' => null]);
        }

        if (! $this->boolean('published')) {
            return;
        }

        $errors = [];
        $lesson = $this->route('lesson');

        if (! $lesson || ! $lesson->activity_id) {
            $errors['activity_id'] = ['The Lesson must have an associated Activity to be published.'];

        } else {
            $lesson->loadMissing('activity');

            if (! $lesson->activity->published) {
                $errors['activity_id'] = ['The associated Activity must be published before the Lesson can be published.'];
            }
        }

        $skills = $this->input('document.skills', []);

        if (! $this->input('deck_id')) {
            $errors['deck_id'] = ['Lesson must have an assigned Deck.'];
        }
        if (! $this->input('dialog_id')) {
            $errors['dialog_id'] = ['Lesson must have an assigned Dialog.'];
        }

        if ($this->input('group') === 'extra') {
            $conditions = $this->input('unlock_conditions', []);

            if (empty($conditions)) {
                $errors['unlock_conditions'] = ['Extra Lessons must have at least one Unlock Condition.'];
            }

            foreach ($conditions as $i => $condition) {
                $prefix = "unlock_conditions.$i";
                if (empty($condition['type'])) {
                    $errors["$prefix.type"] = ["Unlock Condition ".($i + 1)." is missing a type."];
                }
                if (empty($condition['value'])) {
                    $errors["$prefix.value"] = ["Unlock Condition ".($i + 1)." is missing a value."];
                }
            }
        }

        if (count($skills) === 0) {
            $errors['document.skills'] = ['At least one Skill is required.'];
        }

        $blockValidator = new BlockValidator(
            allowedBlockTypes: ['container', 'text', 'chart', 'sentence'],
            recursive: true,
        );

        foreach ($skills as $si => $skill) {
            $prefix = "document.skills.$si";

            if (empty(trim($skill['type'] ?? ''))) {
                $errors["$prefix.type"] = ['Type is required.'];
            }
            if (empty(trim($skill['title'] ?? ''))) {
                $errors["$prefix.title"] = ['Title is required.'];
            }
            if (empty(trim($skill['description'] ?? ''))) {
                $errors["$prefix.description"] = ['Description is required.'];
            }

            $blocks = $skill['blocks'] ?? [];
            if (empty($blocks)) {
                $errors["$prefix.blocks"] = ['Skill must contain at least one Block.'];
                continue;
            }

            $blockValidator->validateBlocks($blocks, "$prefix.blocks", $errors);
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
