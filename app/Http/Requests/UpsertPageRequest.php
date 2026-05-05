<?php

namespace App\Http\Requests;

use App\Models\Page;
use App\Support\Blocks\BlockValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpsertPageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($pageId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'document' => ['required', 'array'],
            'document.schemaVersion' => ['required', 'integer', 'min:1'],
            'document.blocks' => ['required', 'array', 'min:1'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'locale' => ['required', 'string', 'max:10'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:pages,id',
                Rule::notIn(array_filter([$pageId])),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.not_in' => 'A page cannot be its own parent.',
        ];
    }

    protected function passedValidation(): void
    {
        if (! $this->boolean('published')) {
            return;
        }

        $document = $this->input('document', []);
        $blocks = $document['blocks'] ?? [];
        $pageId = $this->route('page')?->id;
        $parentId = $this->integer('parent_id') ?: null;

        $errors = [];

        if ($pageId && $parentId) {
            $descendantIds = $this->getDescendantPageIds($pageId);

            if (in_array($parentId, $descendantIds, true)) {
                $errors['parent_id'] = ['A page cannot use one of its own child pages as its parent.'];
            }
        }

        $blockValidator = new BlockValidator(
            allowedBlockTypes: ['container', 'heading', 'text', 'chart', 'sentence'],
            recursive: true,
        );

        $blockValidator->validateBlocks($blocks, 'document.blocks', $errors);

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function getDescendantPageIds(int $pageId): array
    {
        $descendantIds = [];
        $pendingIds = [$pageId];

        while (! empty($pendingIds)) {
            $childIds = Page::query()
                ->whereIn('parent_id', $pendingIds)
                ->pluck('id')
                ->all();

            $childIds = array_values(array_diff($childIds, $descendantIds));

            if (empty($childIds)) {
                break;
            }

            $descendantIds = array_merge($descendantIds, $childIds);
            $pendingIds = $childIds;
        }

        return $descendantIds;
    }
}
