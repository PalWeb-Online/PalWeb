<?php

namespace App\Http\Requests;

use App\Support\Blocks\BlockValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpsertActivityRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $document = $this->input('document');

        if (! is_array($document)) {
            return;
        }

        $blocks = $document['blocks'] ?? [];

        if (! is_array($blocks)) {
            return;
        }

        $this->stripBlankExerciseTips($blocks);
        $document['blocks'] = $blocks;

        $this->merge(['document' => $document]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
            'title' => ['required', 'string', 'max:255'],
            'document' => ['required', 'array'],
            'document.schemaVersion' => ['required', 'integer', 'min:1'],
            'document.blocks' => ['present', 'array'],
            'published' => ['required', 'boolean'],
        ];
    }

    protected function passedValidation(): void
    {
        if (!$this->boolean('published')) {
            return;
        }

        $errors = [];

        $document = $this->input('document', []);
        $blocks = $document['blocks'] ?? [];

        if (!collect($blocks)->contains(fn ($b) => ($b['type'] ?? null) === 'exercises')) {
            $errors['document.blocks'] = ['At least one Exercises Block is required.'];
        }

        $blockValidator = new BlockValidator(
            allowedBlockTypes: ['text', 'image', 'audio', 'table', 'exercises'],
            recursive: false,
        );

        $blockValidator->validateBlocks($blocks, 'document.blocks', $errors);

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function stripBlankExerciseTips(array &$blocks): void
    {
        foreach ($blocks as &$block) {
            if (! is_array($block)) {
                continue;
            }

            if (($block['type'] ?? null) === 'container' && is_array($block['blocks'] ?? null)) {
                $this->stripBlankExerciseTips($block['blocks']);
            }

            if (($block['type'] ?? null) !== 'exercises' || ! is_array($block['items'] ?? null)) {
                continue;
            }

            foreach ($block['items'] as &$exercise) {
                if (
                    is_array($exercise)
                    && array_key_exists('tip', $exercise)
                    && (! is_string($exercise['tip']) || trim($exercise['tip']) === '')
                ) {
                    unset($exercise['tip']);
                }
            }

            unset($exercise);
        }

        unset($block);
    }
}
