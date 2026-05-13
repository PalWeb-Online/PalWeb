<?php

namespace App\Support\Blocks;

readonly class BlockValidator
{
    public function __construct(
        private array $allowedBlockTypes,
        private bool $recursive = true,
    ) {
    }

    public function validateBlocks(array $blocks, string $path, array &$errors): void
    {
        foreach ($blocks as $index => $block) {
            if (! is_array($block)) {
                $errors["$path.$index"] = ['Block must be an object.'];
                continue;
            }

            $type = $block['type'] ?? null;

            if (! in_array($type, $this->allowedBlockTypes, true)) {
                $errors["$path.type"] = ['This block type is not allowed here.'];
                return;
            }

            match ($type) {
                'container' => $this->validateContainerBlock($block, $path, $errors),
                'heading' => $this->validateHeadingBlock($block, $path, $errors),
                'text' => $this->validateTextBlock($block, $path, $errors),
                'image' => $this->validateImageBlock($block, $path, $errors),
                'audio' => $this->validateAudioBlock($block, $path, $errors),
                'chart' => $this->validateChartBlock($block, $path, $errors),
                'table' => $this->validateTableBlock($block, $path, $errors),
                'sentence' => $this->validateSentenceBlock($block, $path, $errors),
                'exercises' => $this->validateExercisesBlock($block, $path, $errors),
                default => null,
            };
        }
    }

    private function validateContainerBlock(array $block, string $path, array &$errors): void
    {
        $nestedBlocks = $block['blocks'] ?? [];

        if (! is_array($nestedBlocks)) {
            $errors["$path.blocks"] = ['Container blocks must be an array.'];
            return;
        }

        if (count($nestedBlocks) === 0) {
            $errors["$path.blocks"] = ['Container cannot be empty.'];
            return;
        }

        if ($this->recursive) {
            $this->validateBlocks($nestedBlocks, "$path.blocks", $errors);
        }
    }

    private function validateHeadingBlock(array $block, string $path, array &$errors): void
    {
        if (trim((string) ($block['title'] ?? '')) === '') {
            $errors["$path.title"] = ['Heading title cannot be empty.'];
        }

        if (! in_array(($block['level'] ?? null), ['h1', 'h2', 'h3'], true)) {
            $errors["$path.level"] = ['Heading level must be h1, h2, or h3.'];
        }
    }

    private function validateTextBlock(array $block, string $path, array &$errors): void
    {
        if (trim((string) ($block['content'] ?? '')) === '') {
            $errors["$path.content"] = ['Text Block content cannot be empty.'];
        }
    }

    private function validateImageBlock(array $block, string $path, array &$errors): void
    {
        if (trim((string) ($block['media'] ?? '')) === '') {
            $errors["$path.media"] = ['Image Block media cannot be empty.'];
        }
    }

    private function validateAudioBlock(array $block, string $path, array &$errors): void
    {
        if (trim((string) ($block['media'] ?? '')) === '') {
            $errors["$path.media"] = ['Audio Block media cannot be empty.'];
        }
    }

    private function validateTableBlock(array $block, string $path, array &$errors): void
    {
        $columns = $block['columns'] ?? [];
        $rows = $block['rows'] ?? [];

        if (! is_array($columns) || count($columns) < 1) {
            $errors["$path.columns"] = ['Table Block must have at least 1 Column.'];
        }

        if (! is_array($rows) || count($rows) < 1) {
            $errors["$path.rows"] = ['Table Block must have at least 1 Row.'];
        }

        if (! is_array($columns) || ! is_array($rows)) {
            return;
        }

        foreach ($columns as $columnIndex => $column) {
            $label = trim((string) ($column['label'] ?? ''));

            if ($label === '') {
                $errors["$path.columns.$columnIndex.label"] = ['Column label cannot be empty.'];
            }
        }

        foreach ($rows as $rowIndex => $row) {
            $cells = $row['cells'] ?? [];

            foreach ($columns as $column) {
                $columnId = $column['id'] ?? null;
                $cell = $columnId ? (string) ($cells[$columnId] ?? '') : '';

                if (trim($cell) === '') {
                    $errors["$path.rows.$rowIndex.cells"] = ['All cells must be filled.'];
                    break;
                }
            }
        }
    }

    private function validateSentenceBlock(array $block, string $path, array &$errors): void
    {
        if (empty($block['model']) && empty($block['custom'])) {
            $errors[$path] = ['Sentence Block must have Sentence model or custom Sentence.'];
            return;
        }

        if (empty($block['custom'])) {
            return;
        }

        if (trim((string) ($block['custom']['transl'] ?? '')) === '') {
            $errors["$path.custom.transl"] = ['Translation cannot be empty.'];
        }

        $terms = $block['custom']['terms'] ?? [];

        if (! is_array($terms) || count($terms) === 0) {
            $errors["$path.custom.terms"] = ['Sentence must have at least one Term.'];
            return;
        }

        foreach ($terms as $termIndex => $term) {
            if (trim((string) ($term['term'] ?? '')) === '') {
                $errors["$path.custom.terms.$termIndex.term"] = ['Arabic text is required.'];
            }

            if (trim((string) ($term['transc'] ?? '')) === '') {
                $errors["$path.custom.terms.$termIndex.transc"] = ['Transcription is required.'];
            }
        }
    }

    private function validateChartBlock(array $block, string $path, array &$errors): void
    {
        $rows = $block['rows'] ?? [];

        if (! is_array($rows) || count($rows) === 0) {
            $errors["$path.rows"] = ['Chart must have at least one row.'];
            return;
        }

        foreach ($rows as $rowIndex => $row) {
            $items = $row['items'] ?? [];

            if (! is_array($items)) {
                $errors["$path.rows.$rowIndex.items"] = ['Chart row items must be an array.'];
                continue;
            }

            foreach ($items as $itemIndex => $item) {
                if (trim((string) ($item['key'] ?? '')) === '') {
                    $errors["$path.rows.$rowIndex.items.$itemIndex.key"] = ['Key is required.'];
                }

                if (trim((string) ($item['ar'] ?? '')) === '') {
                    $errors["$path.rows.$rowIndex.items.$itemIndex.ar"] = ['Arabic text is required.'];
                }

                if (trim((string) ($item['tr'] ?? '')) === '') {
                    $errors["$path.rows.$rowIndex.items.$itemIndex.tr"] = ['Transcription is required.'];
                }
            }
        }
    }

    private function validateExercisesBlock(array $block, string $path, array &$errors): void
    {
        $items = $block['items'] ?? [];

        if (! is_array($items) || count($items) < 1) {
            $errors["$path.items"] = ['Exercises Block must have at least one Exercise.'];
        }

        $examples = $block['examples'] ?? [];

        if (is_array($examples)) {
            foreach ($examples as $exampleIndex => $example) {
                $prompt = trim((string) ($example['prompt'] ?? ''));
                $answer = trim((string) ($example['answer'] ?? ''));

                if ($prompt === '') {
                    $errors["$path.examples.$exampleIndex.prompt"] = ['Prompt cannot be empty.'];
                }

                if ($answer === '') {
                    $errors["$path.examples.$exampleIndex.answer"] = ['Answer cannot be empty.'];
                }
            }
        }

        if (! is_array($items)) {
            return;
        }

        foreach ($items as $exerciseIndex => $exercise) {
            $this->validateExercise($exercise, "$path.items.$exerciseIndex", $errors);
        }
    }

    private function validateExercise(array $exercise, string $path, array &$errors): void
    {
        $prompts = $exercise['prompts'] ?? [];

        $hasValidPrompt = is_array($prompts) && collect($prompts)->contains(fn ($prompt) => (
                in_array(($prompt['type'] ?? null), ['text', 'audio'], true)
                && trim((string) ($prompt['value'] ?? '')) !== ''
            ));

        if (! $hasValidPrompt) {
            $errors["$path.prompts"] = ['Exercise must have at least one valid Text or Audio prompt.'];
        }

        if (is_array($prompts)) {
            foreach ($prompts as $promptIndex => $prompt) {
                if (trim((string) ($prompt['value'] ?? '')) === '') {
                    $errors["$path.prompts.$promptIndex"] = ['Prompt value cannot be empty.'];
                }
            }
        }

        match (($exercise['type'] ?? null)) {
            'input' => $this->validateInputExercise($exercise, $path, $errors),
            'select' => $this->validateSelectExercise($exercise, $path, $errors),
            'match' => $this->validateMatchExercise($exercise, $path, $errors),
            'sort' => $this->validateSortExercise($exercise, $path, $errors),
            default => $errors["$path.type"] = ['Exercise type must be input, select, or match.'],
        };
    }

    private function validateInputExercise(array $exercise, string $path, array &$errors): void
    {
        $answers = $exercise['answers'] ?? [];

        $hasAny = is_array($answers)
            && collect($answers)->contains(fn ($answer) => trim((string) $answer) !== '');

        if (! $hasAny) {
            $errors["$path.answers"] = ['At least one non-empty accepted answer is required.'];
        }
    }

    private function validateMatchExercise(array $exercise, string $path, array &$errors): void
    {
        $pairs = $exercise['pairs'] ?? [];

        if (! is_array($pairs) || count($pairs) < 2) {
            $errors["$path.pairs"] = ['Matching exercises must have at least 2 pairs.'];
            return;
        }

        foreach ($pairs as $pairIndex => $pair) {
            $start = trim((string) ($pair['start'] ?? ''));
            $end = trim((string) ($pair['end'] ?? ''));

            if ($start === '') {
                $errors["$path.pairs.$pairIndex.start"] = ['Start side cannot be empty.'];
            }

            if ($end === '') {
                $errors["$path.pairs.$pairIndex.end"] = ['End side cannot be empty.'];
            }
        }
    }

    private function validateSelectExercise(array $exercise, string $path, array &$errors): void
    {
        $options = $exercise['options'] ?? [];
        $answerId = $exercise['answerId'] ?? null;

        if (! is_array($options)) {
            $errors["$path.options"] = ['Options must be an array.'];
            return;
        }

        $optionIds = collect($options)->pluck('id')->toArray();

        $anyEmpty = collect($options)->contains(fn ($option) => (
            trim((string) ($option['text'] ?? '')) === ''
        ));

        if ($anyEmpty) {
            $errors["$path.options"] = ['The Options array cannot contain empty text.'];
        }

        if (! $answerId || ! in_array($answerId, $optionIds, true)) {
            $errors["$path.answerId"] = ['A valid correct answer must be selected.'];
        }
    }

    private function validateSortExercise(array $exercise, string $path, array &$errors): void
    {
        $items = $exercise['items'] ?? [];

        if (! is_array($items)) {
            $errors["$path.items"] = ['Items must be an array.'];
            return;
        }

        $anyEmpty = collect($items)->contains(fn ($option) => (
            trim((string) ($option['text'] ?? '')) === ''
        ));

        if ($anyEmpty) {
            $errors["$path.items"] = ['The Items array cannot contain empty text.'];
        }
    }
}
