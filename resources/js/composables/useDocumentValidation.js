export function useDocumentValidation({
    allowedBlockTypes = [],
    recursive = true,
} = {}) {
    const isNonEmptyString = (value) => {
        return typeof value === 'string' && value.trim().length > 0;
    };

    const validateBlocks = (blocks = [], issues = [], path = 'Document') => {
        if (!Array.isArray(blocks)) {
            issues.push(`${path}: Blocks must be an array.`);
            return issues;
        }

        blocks.forEach((block, blockIndex) => {
            const type = block?.type;
            const where = `${path}, Block ${blockIndex + 1}${type ? ` (${type})` : ''}`;

            if (!block || typeof block !== 'object') {
                issues.push(`${where}: Block must be an object.`);
                return;
            }

            if (allowedBlockTypes.length > 0 && !allowedBlockTypes.includes(type)) {
                issues.push(`${where}: This block type is not allowed here.`);
                return;
            }

            if (type === 'container') {
                validateContainerBlock(block, issues, where);
            }

            if (type === 'heading') {
                validateHeadingBlock(block, issues, where);
            }

            if (type === 'text') {
                validateTextBlock(block, issues, where);
            }

            if (type === 'audio') {
                validateAudioBlock(block, issues, where);
            }

            if (type === 'chart') {
                validateChartBlock(block, issues, where);
            }

            if (type === 'table') {
                validateTableBlock(block, issues, where);
            }

            if (type === 'sentence') {
                validateSentenceBlock(block, issues, where);
            }

            if (type === 'exercises') {
                validateExercisesBlock(block, issues, where);
            }
        });

        return issues;
    };

    const validateContainerBlock = (block, issues, where) => {
        const nestedBlocks = block.blocks ?? [];

        if (!Array.isArray(nestedBlocks)) {
            issues.push(`${where}: Container blocks must be an array.`);
            return;
        }

        if (nestedBlocks.length === 0) {
            issues.push(`${where}: Container cannot be empty.`);
            return;
        }

        if (recursive) {
            validateBlocks(nestedBlocks, issues, where);
        }
    };

    const validateHeadingBlock = (block, issues, where) => {
        if (!isNonEmptyString(block.title)) {
            issues.push(`${where}: Heading title cannot be empty.`);
        }

        if (!['h1', 'h2', 'h3'].includes(block.level)) {
            issues.push(`${where}: Heading level must be h1, h2, or h3.`);
        }
    };

    const validateTextBlock = (block, issues, where) => {
        if (!isNonEmptyString(block.content)) {
            issues.push(`${where}: Text Block content cannot be empty.`);
        }
    };

    const validateAudioBlock = (block, issues, where) => {
        if (!isNonEmptyString(block.media)) {
            issues.push(`${where}: Audio Block media cannot be empty.`);
        }
    };

    const validateSentenceBlock = (block, issues, where) => {
        if (!block.model && !block.custom) {
            issues.push(`${where}: Sentence Block must have Sentence model or custom Sentence.`);
            return;
        }

        if (!block.custom) {
            return;
        }

        if (!isNonEmptyString(block.custom.transl)) {
            issues.push(`${where}: Translation cannot be empty.`);
        }

        const terms = block.custom.terms ?? [];

        if (!Array.isArray(terms) || terms.length === 0) {
            issues.push(`${where}: Sentence must have at least one Term.`);
            return;
        }

        terms.forEach((term, termIndex) => {
            if (!isNonEmptyString(term?.term)) {
                issues.push(`${where}: Term ${termIndex + 1} Arabic text is required.`);
            }

            if (!isNonEmptyString(term?.transc)) {
                issues.push(`${where}: Term ${termIndex + 1} transcription is required.`);
            }
        });
    };

    const validateChartBlock = (block, issues, where) => {
        const rows = block.rows ?? [];

        if (!Array.isArray(rows) || rows.length === 0) {
            issues.push(`${where}: Chart Block must have at least one row.`);
            return;
        }

        rows.forEach((row, rowIndex) => {
            const items = row?.items ?? [];

            if (!Array.isArray(items)) {
                issues.push(`${where}: Row ${rowIndex + 1} items must be an array.`);
                return;
            }

            items.forEach((item, itemIndex) => {
                if (!isNonEmptyString(item?.key)) {
                    issues.push(`${where}: Row ${rowIndex + 1}, Item ${itemIndex + 1} key is required.`);
                }

                if (!isNonEmptyString(item?.ar)) {
                    issues.push(`${where}: Row ${rowIndex + 1}, Item ${itemIndex + 1} Arabic text is required.`);
                }

                if (!isNonEmptyString(item?.tr)) {
                    issues.push(`${where}: Row ${rowIndex + 1}, Item ${itemIndex + 1} transcription is required.`);
                }
            });
        });
    };

    const validateTableBlock = (block, issues, where) => {
        const columns = block.columns ?? [];
        const rows = block.rows ?? [];

        if (!Array.isArray(columns) || columns.length < 1) {
            issues.push(`${where}: Table Block must have at least 1 Column.`);
        }

        if (!Array.isArray(rows) || rows.length < 1) {
            issues.push(`${where}: Table Block must have at least 1 Row.`);
        }

        if (!Array.isArray(columns) || !Array.isArray(rows)) {
            return;
        }

        columns.forEach((column, columnIndex) => {
            if (!isNonEmptyString(column?.label)) {
                issues.push(`${where}: Column ${columnIndex + 1} label cannot be empty.`);
            }
        });

        rows.forEach((row, rowIndex) => {
            columns.forEach((column, columnIndex) => {
                const columnId = column?.id;
                const cell = columnId ? row?.cells?.[columnId] : null;

                if (!isNonEmptyString(cell)) {
                    issues.push(`${where}: Row ${rowIndex + 1}, Column ${columnIndex + 1} cell cannot be empty.`);
                }
            });
        });
    };

    const validateExercisesBlock = (block, issues, where) => {
       const examples = block.examples ?? [];

        if (Array.isArray(examples)) {
            examples.forEach((example, exampleIndex) => {
                if (!isNonEmptyString(example?.prompt)) {
                    issues.push(`${where}: Example ${exampleIndex + 1} prompt is empty.`);
                }

                if (!isNonEmptyString(example?.answer)) {
                    issues.push(`${where}: Example ${exampleIndex + 1} answer is empty.`);
                }
            });
        }

        const items = block.items ?? [];

        if (!Array.isArray(items) || items.length === 0) {
            issues.push(`${where}: Exercises Block must have at least one Exercise.`);
            return;
        }

        items.forEach((exercise, exerciseIndex) => {
            validateExercise(exercise, issues, `${where}: Exercise ${exerciseIndex + 1} (${exercise?.type})`);
        });
    };

    const validateExercise = (exercise, issues, where) => {
        const prompts = exercise?.prompts ?? [];
        const hasValidTextOrAudio = Array.isArray(prompts) && prompts.some((prompt) => {
            return ['text', 'audio'].includes(prompt?.type) && isNonEmptyString(prompt?.value);
        });

        if (!hasValidTextOrAudio) {
            issues.push(`${where}: must have at least one non-empty Text or Audio prompt.`);
        }

        if (Array.isArray(prompts)) {
            prompts.forEach((prompt, promptIndex) => {
                if (!isNonEmptyString(prompt?.value)) {
                    issues.push(`${where}: Prompt ${promptIndex + 1} (${prompt?.type}) cannot be empty.`);
                }
            });
        }

        if (exercise?.type === 'input') {
            validateInputExercise(exercise, issues, where);
        } else if (exercise?.type === 'select') {
            validateSelectExercise(exercise, issues, where);
        } else if (exercise?.type === 'match') {
            validateMatchExercise(exercise, issues, where);
        } else {
            issues.push(`${where}: Exercise type must be input, select, or match.`);
        }
    };

    const validateInputExercise = (exercise, issues, where) => {
        const answers = Array.isArray(exercise?.answers) ? exercise.answers : [];
        const hasAnyAnswer = answers.some((answer) => isNonEmptyString(answer));

        if (!hasAnyAnswer) {
            issues.push(`${where}: must have at least one non-empty accepted answer.`);
        }
    };

    const validateSelectExercise = (exercise, issues, where) => {
        const options = Array.isArray(exercise?.options) ? exercise.options : [];
        const optionIds = options.map((option) => option.id);

        const anyEmpty = options.some((option) => !isNonEmptyString(option?.text));

        if (anyEmpty) {
            issues.push(`${where}: option text cannot be empty.`);
        }

        if (!exercise?.answerId || !optionIds.includes(exercise.answerId)) {
            issues.push(`${where}: a correct answer must be selected.`);
        }
    };

    const validateMatchExercise = (exercise, issues, where) => {
        const pairs = Array.isArray(exercise?.pairs) ? exercise.pairs : [];

        if (pairs.length < 2) {
            issues.push(`${where}: must have at least 2 pairs.`);
            return;
        }

        pairs.forEach((pair, pairIndex) => {
            if (!isNonEmptyString(pair?.start)) {
                issues.push(`${where}: pair ${pairIndex + 1} start side cannot be empty.`);
            }

            if (!isNonEmptyString(pair?.end)) {
                issues.push(`${where}: pair ${pairIndex + 1} end side cannot be empty.`);
            }
        });
    };

    return {
        isNonEmptyString,
        validateBlocks,
    };
}
