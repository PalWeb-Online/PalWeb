import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";

export function useLessonValidation({
                                        form,
                                        selectedDeck = null,
                                        selectedDialog = null,
                                        lessonActivity = null,
                                        allowedBlockTypes,
                                    }) {
    const {
        isNonEmptyString,
        validateBlocks,
    } = useDocumentResourceValidation({
        allowedBlockTypes,
        recursive: true,
    });

    const getValue = (value) => value?.value ?? value ?? null;

    const validationIssues = computed(() => {
        const issues = [];

        if (!isNonEmptyString(form.title)) {
            issues.push('Title is required.');
        }

        return issues;
    });

    const isValidRequest = computed(() => validationIssues.value.length === 0);

    const publishIssues = computed(() => {
        const issues = [];

        const deck = getValue(selectedDeck);
        const dialog = getValue(selectedDialog);
        const activity = getValue(lessonActivity);

        if (form.group === 'extra') {
            const conditions = form.unlock_conditions || [];

            if (conditions.length === 0) {
                issues.push('Extra Lessons must have at least one Unlock Condition.');
            }

            conditions.forEach((condition, index) => {
                const name = `Unlock Condition ${index + 1}`;

                if (!isNonEmptyString(condition.type)) {
                    issues.push(`${name}: Type is required.`);
                }

                if (!condition.value) {
                    issues.push(`${name}: Value is required.`);
                }
            });
        }

        const skills = form.document?.skills || [];

        if (skills.length === 0) {
            issues.push('At least one Skill is required.');
        }

        skills.forEach((skill, skillIndex) => {
            const skillName = `Skill ${skillIndex + 1}`;

            if (!isNonEmptyString(skill.type)) {
                issues.push(`${skillName}: Type is required.`);
            }

            if (!isNonEmptyString(skill.title)) {
                issues.push(`${skillName}: Title is required.`);
            }

            if (!isNonEmptyString(skill.description)) {
                issues.push(`${skillName}: Description is required.`);
            }

            const blocks = skill.blocks ?? [];

            if (!Array.isArray(blocks) || blocks.length === 0) {
                issues.push(`${skillName}: Skill must have at least one Block.`);
            } else {
                validateBlocks(blocks, issues, skillName);
            }
        });

        if (!form.deck_id) {
            issues.push('Lesson must have an assigned Deck.');

        } else if (!deck) {
            issues.push('Selected Deck could not be validated.');

        } else if (deck.private) {
            issues.push('The Deck must be public before the Lesson can be published.');
        }

        if (!form.dialog_id) {
            issues.push('Lesson must have an assigned Dialog.');

        } else if (!dialog) {
            issues.push('Selected Dialog could not be validated.');

        } else if (!dialog.published) {
            issues.push('The Dialog must be published before the Lesson can be published.');
        }

        if (!activity?.id) {
            issues.push('Lesson must have an assigned Activity.');

        } else if (!activity.published) {
            issues.push('The Activity must be published before the Lesson can be published.');
        }

        return issues;
    });

    const isPublishable = computed(() => publishIssues.value.length === 0);

    return {
        validationIssues,
        isValidRequest,
        publishIssues,
        isPublishable,
    };
}
