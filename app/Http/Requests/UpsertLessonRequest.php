<?php

namespace App\Http\Requests;

use App\Models\Lesson;
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
        ];
    }

    protected function passedValidation(): void
    {
        if (!$this->boolean('published')) {
            return;
        }

        $errors = [];
        $lesson = $this->route('lesson');

        if (!$lesson || !$lesson->activity_id) {
            $errors['activity_id'] = ['The Lesson must have an associated Activity to be published.'];

        } else {
            $lesson->loadMissing('activity');

            if (!$lesson->activity->published) {
                $errors['activity_id'] = ['The associated Activity must be published before the Lesson can be published.'];
            }
        }

        $skills = $this->input('document.skills', []);

        if (!$this->input('unit_id')) $errors['unit_id'] = ['Lesson must be attached to a Unit to be published.'];
        if (!$this->input('deck_id')) $errors['deck_id'] = ['Lesson must have an assigned Deck.'];
        if (!$this->input('dialog_id')) $errors['dialog_id'] = ['Lesson must have an assigned Dialog.'];

        if (count($skills) === 0) {
            $errors['document.skills'] = ['At least one Skill is required.'];
        }

        foreach ($skills as $si => $skill) {
            $prefix = "document.skills.$si";

            if (empty(trim($skill['type'] ?? ''))) $errors["$prefix.type"] = ['Type is required.'];
            if (empty(trim($skill['title'] ?? ''))) $errors["$prefix.title"] = ['Title is required.'];
            if (empty(trim($skill['description'] ?? ''))) $errors["$prefix.description"] = ['Description is required.'];

            $blocks = $skill['blocks'] ?? [];
            if (empty($blocks)) {
                $errors["$prefix.blocks"] = ['Skill must contain at least one Block.'];
            }

            foreach ($blocks as $bi => $block) {
                $blockPrefix = "$prefix.blocks.$bi";
                $type = $block['type'] ?? '';

                if ($type === 'text' && empty(trim($block['content'] ?? ''))) {
                    $errors["$blockPrefix.content"] = ['Text Block content cannot be empty.'];
                }

                if ($type === 'sentence') {
                    if (empty($block['model']) && empty($block['custom'])) {
                        $errors[$blockPrefix] = ['Sentence Block must have Sentence model or custom Sentence.'];

                    } elseif (!empty($block['custom'])) {
                        if (empty(trim($block['custom']['transl'] ?? ''))) {
                            $errors["$blockPrefix.custom.transl"] = ['Translation cannot be empty.'];
                        }

                        $terms = $block['custom']['terms'] ?? [];

                        if (empty($terms)) {
                            $errors["$blockPrefix.custom.terms"] = ['Sentence must have at least one Term.'];

                        } else {
                            foreach ($terms as $ti => $term) {
                                if (empty(trim($term['term'] ?? ''))) $errors["$blockPrefix.custom.terms.$ti.term"] = ['Arabic text is required.'];
                                if (empty(trim($term['transc'] ?? ''))) $errors["$blockPrefix.custom.terms.$ti.transc"] = ['Transcription is required.'];
                            }
                        }
                    }
                }

                if ($type === 'chart') {
                    $rows = $block['rows'] ?? [];

                    if (empty($rows)) {
                        $errors["$blockPrefix.rows"] = ['Chart must have at least one row.'];

                    } else {
                        foreach ($rows as $ri => $row) {
                            $items = $row['items'] ?? [];
                            foreach ($items as $ii => $item) {
                                if (empty(trim($item['key'] ?? ''))) $errors["$blockPrefix.rows.$ri.items.$ii.key"] = ['Key is required.'];
                                if (empty(trim($item['ar'] ?? ''))) $errors["$blockPrefix.rows.$ri.items.$ii.ar"] = ['Arabic text is required.'];
                                if (empty(trim($item['tr'] ?? ''))) $errors["$blockPrefix.rows.$ri.items.$ii.tr"] = ['Transcription is required.'];
                            }
                        }
                    }
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
