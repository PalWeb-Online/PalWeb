import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";

export function usePageValidation({
                                      form,
                                      page,
                                      backendErrors,
                                      descendantIds,
                                      allowedBlockTypes,
                                  }) {
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
            errors.slug = 'Slug is required.';
        }

        if (!isNonEmptyString(form.title)) {
            errors.title = 'Title is required.';
        }

        return errors;
    });

    const publishIssues = computed(() => {
        const issues = [];

        if (page.value?.id && Number(form.parent_id) === Number(page.value.id)) {
            issues.push('A page cannot be its own parent.');
        }

        if (form.parent_id && descendantIds.value.map(Number).includes(Number(form.parent_id))) {
            issues.push('A page cannot use one of its own child pages as its parent.');
        }

        if (!form.document?.schemaVersion) {
            issues.push('Document schemaVersion is required.');
        }

        const blocks = form.document?.blocks ?? [];

        if (!Array.isArray(blocks) || blocks.length === 0) {
            issues.push('At least one Block is required.');
        } else {
            validateBlocks(blocks, issues, 'Page');
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
