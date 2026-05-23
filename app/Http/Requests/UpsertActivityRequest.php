<?php

namespace App\Http\Requests;

use App\Support\Blocks\BlockValidator;
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
}
