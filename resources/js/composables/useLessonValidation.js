import {computed} from "vue";
import {useDocumentValidation} from "./useDocumentValidation.js";

export function useLessonValidation({
    form,
    lesson,
}) {
    const {
        isNonEmptyString,
        validateBlocks,
    } = useDocumentValidation({
        allowedBlockTypes: ['container', 'text', 'chart', 'sentence'],
        recursive: true,
    });

    const publishIssues = computed(() => {
        const issues = [];

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

        if (!lesson?.deck?.id && !form.deck_id) {
            issues.push('Lesson must have an assigned Deck.');

        } else if (lesson?.deck && lesson.deck?.private) {
            issues.push('The Deck must be public before the Lesson can be published.');
        }

        if (!lesson?.dialog?.id && !form.dialog_id) {
            issues.push('Lesson must have an assigned Dialog.');

        } else if (lesson?.dialog && !lesson.dialog?.published) {
            issues.push('The Dialog must be published before the Lesson can be published.');
        }

        if (!lesson?.activity?.id) {
            issues.push('Lesson must have an assigned Activity.');

        } else if (!lesson.activity.published) {
            issues.push('The Activity must be published before the Lesson can be published.');
        }

        return issues;
    });

    const isPublishable = computed(() => publishIssues.value.length === 0);

    return {
        publishIssues,
        isPublishable,
    };
}
