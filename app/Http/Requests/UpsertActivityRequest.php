<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpsertActivityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
            'title' => ['required', 'string', 'max:255'],
            'document' => ['required', 'array'],
            'document.schemaVersion' => ['required', 'integer'],
            'document.blocks' => ['required', 'array'],
            'published' => ['required', 'boolean'],
        ];
    }

    protected function passedValidation(): void
    {
        if (!$this->boolean('published')) {
            return;
        }

        $document = $this->input('document', []);
        $blocks = $document['blocks'] ?? [];

        $errors = [];

        $hasExercisesBlock = collect($blocks)->contains(fn ($b) => ($b['type'] ?? null) === 'exercises');
        if (!$hasExercisesBlock) {
            $errors['document.blocks'] = ['At least one Exercises Block is required.'];
        }

        foreach ($blocks as $bi => $block) {
            $type = $block['type'] ?? null;

            if ($type === 'text') {
                $content = trim((string)($block['content'] ?? ''));
                if ($content === '') {
                    $errors["document.blocks.$bi.content"] = ['Text Block content cannot be empty.'];
                }
            }

            if ($type === 'audio') {
                $content = trim((string)($block['media'] ?? ''));
                if ($content === '') {
                    $errors["document.blocks.$bi.media"] = ['Audio Block media cannot be empty.'];
                }
            }

            if ($type === 'table') {
                $cols = $block['columns'] ?? [];
                $rows = $block['rows'] ?? [];

                if (!is_array($cols) || count($cols) < 1) {
                    $errors["document.blocks.$bi.columns"] = ['Table Block must have at least 1 Column.'];
                }
                if (!is_array($rows) || count($rows) < 1) {
                    $errors["document.blocks.$bi.rows"] = ['Table Block must have at least 1 Row.'];
                }

                foreach ($cols as $ci => $col) {
                    $label = trim((string)($col['label'] ?? ''));
                    if ($label === '') {
                        $errors["document.blocks.$bi.columns.$ci.label"] = ['Column label cannot be empty.'];
                    }
                }

                foreach ($rows as $ri => $row) {
                    $cells = $row['cells'] ?? [];
                    foreach ($cols as $ci => $col) {
                        $colId = $col['id'] ?? null;
                        $cell = $colId ? (string)($cells[$colId] ?? '') : '';
                        if (trim($cell) === '') {
                            $errors["document.blocks.$bi.rows.$ri.cells"] = ['All cells must be filled.'];
                            break;
                        }
                    }
                }
            }

            if ($type === 'exercises') {
                $items = $block['items'] ?? [];

                if (!is_array($items) || count($items) < 1) {
                    $errors["document.blocks.$bi.items"] = ['Exercises Block must have at least one Exercise.'];
                }

                foreach ($block['examples'] as $ei => $ex) {
                    $prompt = trim((string)($ex['prompt'] ?? ''));
                    $answer = trim((string)($ex['answer'] ?? ''));

                    if ($prompt === '') {
                        $errors["document.blocks.$bi.examples.$ei.prompt"] = ['Prompt cannot be empty.'];
                    }

                    if ($answer === '') {
                        $errors["document.blocks.$bi.examples.$ei.answer"] = ['Answer cannot be empty.'];
                    }
                }

                foreach ($items as $ei => $ex) {
                    $prompt = trim((string)($ex['prompt'] ?? ''));
                    if ($prompt === '') {
                        $errors["document.blocks.$bi.items.$ei.prompt"] = ['Prompt cannot be empty.'];
                    }

                    foreach ($ex['images'] as $ii => $url) {
                        if (trim((string)$url) === '') {
                            $errors["document.blocks.$bi.items.$ei.images.$ii"] = ['Image URL cannot be empty.'];
                        }
                    }

                    if (($ex['type'] ?? null) === 'input') {
                        $answers = $ex['answers'] ?? [];
                        $hasAny = is_array($answers) && collect($answers)->contains(fn ($a) => trim((string)$a) !== '');
                        if (!$hasAny) {
                            $errors["document.blocks.$bi.items.$ei.answers"] = ['At least one non-empty accepted answer is required.'];
                        }
                    }

                    if (($ex['type'] ?? null) === 'select') {
                        $options = $ex['options'] ?? [];
                        $answerId = $ex['answerId'] ?? null;

                        $optionIds = collect($options)->pluck('id')->toArray();

                        $anyEmpty = collect($options)->contains(fn ($o) => trim((string)($o['text'] ?? '')) === '');
                        if ($anyEmpty) {
                            $errors["document.blocks.$bi.items.$ei.options"] = ['Options cannot be empty.'];
                        }

                        if (!$answerId || !in_array($answerId, $optionIds)) {
                            $errors["document.blocks.$bi.items.$ei.answerId"] = ['A valid correct answer must be selected.'];
                        }
                    }

                    if (($ex['type'] ?? null) === 'match') {
                        $pairs = $ex['pairs'] ?? [];

                        if (!is_array($pairs) || count($pairs) < 2) {
                            $errors["document.blocks.$bi.items.$ei.pairs"] = ['Matching exercises must have at least 2 pairs.'];
                            continue;
                        }

                        foreach ($pairs as $pi => $pair) {
                            $start = trim((string)($pair['start'] ?? ''));
                            $end = trim((string)($pair['end'] ?? ''));

                            if ($start === '') {
                                $errors["document.blocks.$bi.items.$ei.pairs.$pi.start"] = ['Start side cannot be empty.'];
                            }
                            if ($end === '') {
                                $errors["document.blocks.$bi.items.$ei.pairs.$pi.end"] = ['End side cannot be empty.'];
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
