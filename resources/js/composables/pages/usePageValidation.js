import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";
import {useI18n} from "vue-i18n";

export function usePageValidation({
                                      form,
                                      page,
                                      backendErrors,
                                      descendantIds,
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

        if (!isNonEmptyString(form.slug)) {
            errors.slug = t('validation.required', {field: t('forms.fields.slug')});
        }

        if (!isNonEmptyString(form.title)) {
            errors.title = t('validation.required', {field: t('forms.fields.title')});
        }

        return errors;
    });

    const publishIssues = computed(() => {
        const issues = [];

        if (page.value?.id && Number(form.parent_id) === Number(page.value.id)) {
            issues.push(t('page.validation.self-is-parent'));
        }

        if (form.parent_id && descendantIds.value.map(Number).includes(Number(form.parent_id))) {
            issues.push(t('page.validation.child-is-parent'));
        }

        const blocks = form.document?.blocks ?? [];

        if (!Array.isArray(blocks) || blocks.length === 0) {
            issues.push(t('validation.min-items', {item: t('block.key'), min: 1}));
        } else {
            validateBlocks(blocks, issues, t('page.key'));
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
