import {computed, unref} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useLessonValidation({
                                        form,
                                        backendErrors,
                                        selectedDeck = null,
                                        selectedDialog = null,
                                        lessonActivity = null,
                                        allowedBlockTypes,
                                    }) {
    const {t} = useI18n();

    const {
        isNonEmptyString,
        validateBlocks,
        useValidationState,
    } = useDocumentResourceValidation({
        allowedBlockTypes,
        recursive: true,
    });

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.title)) {
            errors.title = t('validation.required', {field: t('forms.fields.title')});
        }

        return errors;
    });

    const publishIssues = computed(() => {
        const issues = [];

        const deck = unref(selectedDeck);
        const dialog = unref(selectedDialog);
        const activity = unref(lessonActivity);

        if (form.group === 'extra') {
            const conditions = form.unlock_conditions || [];

            if (conditions.length === 0) {
                issues.push(t('lesson.validation.min-unlock-conditions'));
            }

            conditions.forEach((condition, index) => {
                const name = `${t('lesson.unlock-condition.key')} ${index + 1}`;

                if (!isNonEmptyString(condition.type)) {
                    issues.push(`${name}: ${t('validation.required', {field: t('lesson.unlock-condition.type')})}`);
                }

                if (!condition.value) {
                    issues.push(`${name}: ${t('validation.required', {field: t('lesson.unlock-condition.value')})}`);
                }
            });
        }

        const skills = form.document?.skills || [];

        if (skills.length === 0) {
            issues.push(t('validation.min-items', {item: t('skill.key'), min: 1}));
        }

        skills.forEach((skill, skillIndex) => {
            const skillName = `${t('skill.key')} ${skillIndex + 1}`;

            if (!isNonEmptyString(skill.type)) {
                issues.push(`${skillName}: ${t('validation.required', {field: t('forms.fields.type')})}`);
            }

            if (!isNonEmptyString(skill.title)) {
                issues.push(`${skillName}: ${t('validation.required', {field: t('forms.fields.title')})}`);
            }

            if (!isNonEmptyString(skill.description)) {
                issues.push(`${skillName}: ${t('validation.required', {field: t('forms.fields.description')})}`);
            }

            const blocks = skill.blocks ?? [];

            if (!Array.isArray(blocks) || blocks.length === 0) {
                issues.push(`${skillName}: ${t('validation.min-items', {item: 'Block', min: 1})}`);
            } else {
                validateBlocks(blocks, issues, skillName);
            }
        });

        if (!form.deck_id) {
            issues.push(t('validation.required-dependency', {model: t('deck.key')}));

        } else if (!deck) {
            issues.push(t('validation.invalid-dependency', {model: t('deck.key')}));

        } else if (deck.private) {
            issues.push(t('validation.hidden-dependency', {model: t('deck.key')}));
        }

        if (!form.dialog_id) {
            issues.push(t('validation.required-dependency', {model: t('dialog.key')}));

        } else if (!dialog) {
            issues.push(t('validation.invalid-dependency', {model: t('dialog.key')}));

        } else if (!dialog.published) {
            issues.push(t('validation.hidden-dependency', {model: t('dialog.key')}));
        }

        if (!activity?.id) {
            issues.push(t('validation.required-dependency', {model: t('activity.key')}));

        } else if (!activity.published) {
            issues.push(t('validation.hidden-dependency', {model: t('activity.key')}));
        }

        return issues;
    });

    const isPublishable = computed(() => publishIssues.value.length === 0);

    const {
        isValidRequest,
        validationErrors,
    } = useValidationState({
        frontendErrors,
        backendErrors,
    });

    return {
        isValidRequest,
        validationErrors,
        publishIssues,
        isPublishable,
    };
}
