import {useResourceValidation} from "../resources/useResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useDocumentResourceValidation({
                                                  allowedBlockTypes = [],
                                                  recursive = true,
                                              } = {}) {
    const {t} = useI18n();

    const resourceValidation = useResourceValidation();

    const {
        isNonEmptyString,
    } = resourceValidation;

    const validateBlocks = (blocks = [], issues = [], path = t('document.key')) => {
        if (!Array.isArray(blocks)) {
            issues.push(`${path}: ${t('validation.array', {field: t('document.fields.blocks')})}: ${blocks}`);
            return issues;
        }

        blocks.forEach((block, blockIndex) => {
            const type = block?.type;
            const where = `${path}, ${t('block.key')} ${blockIndex + 1}${type ? ` (${t(`block.type.${type}`)})` : ''}`;

            if (!block || typeof block !== 'object') {
                issues.push(`${where}: ${t('validation.object', {field: t('block.key')})}: ${block}`);
                return;
            }

            if (allowedBlockTypes.length > 0 && !allowedBlockTypes.includes(type)) {
                issues.push(`${where}: ${t('document.validation.invalid-block')}`);
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

            if (type === 'image') {
                validateImageBlock(block, issues, where);
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
            issues.push(`${where}: ${t('validation.array', {field: t('block.type.container')})}`);
            return;
        }

        if (nestedBlocks.length === 0) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('block.key'), min: 1})}`);
            return;
        }

        if (recursive) {
            validateBlocks(nestedBlocks, issues, where);
        }
    };

    const validateHeadingBlock = (block, issues, where) => {
        if (!isNonEmptyString(block.title)) {
            issues.push(`${where}: ${t('validation.required', {field: t('forms.fields.title')})}`);
        }

        if (!['h1', 'h2', 'h3'].includes(block.level)) {
            issues.push(`${where}: ${t('document.validation.heading-type')}`);
        }
    };

    const validateTextBlock = (block, issues, where) => {
        if (!isNonEmptyString(block.content)) {
            issues.push(`${where}: ${t('validation.required', {field: t('block.type.text')})}`);
        }
    };

    const validateImageBlock = (block, issues, where) => {
        if (!isNonEmptyString(block.media)) {
            issues.push(`${where}: ${t('validation.required', {field: t('block.type.image')})}`);
        }
    };

    const validateAudioBlock = (block, issues, where) => {
        if (!isNonEmptyString(block.media)) {
            issues.push(`${where}: ${t('validation.required', {field: t('block.type.audio')})}`);
        }
    };

    const validateSentenceBlock = (block, issues, where) => {
        if (!block.model && !block.custom) {
            issues.push(`${where}: ${t('document.validation.sentence-block-content')}`);
            return;
        }

        if (!block.custom) {
            return;
        }

        if (!isNonEmptyString(block.custom.transl)) {
            issues.push(`${where}: ${t('validation.required', {field: t('sentence.fields.translation')})}`);
        }

        const terms = block.custom.terms ?? [];

        if (!Array.isArray(terms) || terms.length === 0) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('term.key'), min: 1})}`);
            return;
        }

        terms.forEach((term, termIndex) => {
            if (!isNonEmptyString(term?.term)) {
                issues.push(`${where}: ${t('validation.required', {field: `${t('term.key')} ${termIndex + 1}: ${t('term.key')}`})}`);
            }

            if (!isNonEmptyString(term?.transc)) {
                issues.push(`${where}: ${t('validation.required', {field: `${t('term.key')} ${termIndex + 1}: ${t('sentence.fields.transcription')}`})}`);
            }
        });
    };

    const validateChartBlock = (block, issues, where) => {
        const rows = block.rows ?? [];

        if (!Array.isArray(rows) || rows.length === 0) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('document.add.row'), min: 1})}`);
            return;
        }

        rows.forEach((row, rowIndex) => {
            const items = row?.items ?? [];

            if (!Array.isArray(items)) {
                issues.push(`${where}: ${t('validation.array', {field: t('block.fields.row-items', {index: rowIndex + 1})})}`);
                return;
            }

            items.forEach((item, itemIndex) => {
                if (!isNonEmptyString(item?.key)) {
                    issues.push(`${where}: ${t('validation.required', {
                        field: t('block.fields.row-item-key', {
                            rowIndex: rowIndex + 1,
                            itemIndex: itemIndex + 1
                        })
                    })}`);
                }

                if (!isNonEmptyString(item?.ar)) {
                    issues.push(`${where}: ${t('validation.required', {
                        field: t('block.fields.row-item-arabic', {
                            rowIndex: rowIndex + 1,
                            itemIndex: itemIndex + 1
                        })
                    })}`);
                }

                if (!isNonEmptyString(item?.tr)) {
                    issues.push(`${where}: ${t('validation.required', {
                        field: t('block.fields.row-item-transcription', {
                            rowIndex: rowIndex + 1,
                            itemIndex: itemIndex + 1
                        })
                    })}`);
                }
            });
        });
    };

    const validateTableBlock = (block, issues, where) => {
        const columns = block.columns ?? [];
        const rows = block.rows ?? [];

        if (!Array.isArray(columns) || columns.length < 1) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('block.element.column'), min: 1})}`);
        }

        if (!Array.isArray(rows) || rows.length < 1) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('block.element.row'), min: 1})}`);
        }

        if (!Array.isArray(columns) || !Array.isArray(rows)) {
            return;
        }

        columns.forEach((column, columnIndex) => {
            if (!isNonEmptyString(column?.label)) {
                issues.push(`${where}: ${t('validation.required', {field: t('block.fields.column-label', {index: columnIndex + 1})})}`);
            }
        });

        rows.forEach((row, rowIndex) => {
            columns.forEach((column, columnIndex) => {
                const columnId = column?.id;
                const cell = columnId ? row?.cells?.[columnId] : null;

                if (!isNonEmptyString(cell)) {
                    issues.push(`${where}: ${t('validation.required', {
                        field: t('block.fields.column-row-cell', {
                            rowIndex: rowIndex + 1,
                            columnIndex: columnIndex + 1
                        })
                    })}`);
                }
            });
        });
    };

    const validateExercisesBlock = (block, issues, where) => {
        const examples = block.examples ?? [];

        if (Array.isArray(examples)) {
            examples.forEach((example, exampleIndex) => {
                if (!isNonEmptyString(example?.prompt)) {
                    issues.push(`${where}: ${t('validation.required', {field: t('exercise.fields.example-prompt', {index: exampleIndex + 1})})}`);
                }

                if (!isNonEmptyString(example?.answer)) {
                    issues.push(`${where}: ${t('validation.required', {field: t('exercise.fields.example-answer', {index: exampleIndex + 1})})}`);
                }
            });
        }

        const items = block.items ?? [];

        if (!Array.isArray(items) || items.length === 0) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('exercise.key'), min: 1})}`);
            return;
        }

        items.forEach((exercise, exerciseIndex) => {
            validateExercise(exercise, issues, `${where}: ${t('exercise.key')} ${exerciseIndex + 1} (${t(`exercise.type.${exercise?.type}`)})`);
        });
    };

    const validateExercise = (exercise, issues, where) => {
        const prompts = exercise?.prompts ?? [];
        const hasValidTextOrAudio = Array.isArray(prompts) && prompts.some((prompt) => {
            return ['text', 'audio'].includes(prompt?.type) && isNonEmptyString(prompt?.value);
        });

        if (!hasValidTextOrAudio) {
            issues.push(`${where}: ${t('document.validation.exercise-prompt')}`);
        }

        if (Array.isArray(prompts)) {
            prompts.forEach((prompt, promptIndex) => {
                if (!isNonEmptyString(prompt?.value)) {
                    issues.push(`${where}: ${t('validation.required', {
                        field: t('exercise.fields.prompt', {
                            index: promptIndex + 1,
                            type: t(`block.type.${prompt?.type}`)
                        })
                    })}`);
                }
            });
        }

        if (exercise?.type === 'input') {
            validateInputExercise(exercise, issues, where);
        } else if (exercise?.type === 'match') {
            validateMatchExercise(exercise, issues, where);
        } else if (exercise?.type === 'select') {
            validateSelectExercise(exercise, issues, where);
        } else if (exercise?.type === 'sort') {
            validateSortExercise(exercise, issues, where);
        } else {
            issues.push(`${where}: ${t('document.validation.exercises-block-type')}`);
        }
    };

    const validateInputExercise = (exercise, issues, where) => {
        const answers = Array.isArray(exercise?.answers) ? exercise.answers : [];
        const hasAnyAnswer = answers.some((answer) => isNonEmptyString(answer));

        if (!hasAnyAnswer) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('exercise.fields.input-answer'), min: 1})}`);
        }
    };

    const validateMatchExercise = (exercise, issues, where) => {
        const pairs = Array.isArray(exercise?.pairs) ? exercise.pairs : [];

        if (pairs.length < 2) {
            issues.push(`${where}: ${t('validation.min-items', {item: t('exercise.fields.pairs'), min: 2})}`);
            return;
        }

        pairs.forEach((pair, pairIndex) => {
            if (!isNonEmptyString(pair?.start)) {
                issues.push(`${where}: ${t('validate.required', {field: t('exercise.pair-start', {index: pairIndex + 1})})}`);
            }

            if (!isNonEmptyString(pair?.end)) {
                issues.push(`${where}: ${t('validate.required', {field: t('exercise.pair-end', {index: pairIndex + 1})})}`);
            }
        });
    };

    const validateSelectExercise = (exercise, issues, where) => {
        const options = Array.isArray(exercise?.options) ? exercise.options : [];
        const optionIds = options.map((option) => option.id);

        const anyEmpty = options.some((option) => !isNonEmptyString(option?.text));

        if (anyEmpty) {
            issues.push(`${where}: ${t('validation.non-empty-field', {field: t('exercise.fields.select-options')})}`);
        }

        if (!exercise?.answerId || !optionIds.includes(exercise.answerId)) {
            issues.push(`${where}: ${t('document.validation.select-exercise-answer')}`);
        }
    };

    const validateSortExercise = (exercise, issues, where) => {
        const items = Array.isArray(exercise?.items) ? exercise.items : [];

        const anyEmpty = items.some((item) => !isNonEmptyString(item?.text));

        if (anyEmpty) {
            issues.push(`${where}: ${t('validation.non-empty-field', {field: t('exercise.fields.sort-items')})}`);
        }
    };

    return {
        ...resourceValidation,
        validateBlocks,
    };
}
