import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";

export function useActivityValidation({
                                          form,
                                          backendErrors,
                                          allowedBlockTypes,
                                      }) {
    const {
        isNonEmptyString,
        validateBlocks,
        useValidationState,
    } = useDocumentResourceValidation({
        allowedBlockTypes,
        recursive: false,
    });

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.title)) {
            errors.title = 'Title is required.';
        }

        return errors;
    });

    const publishIssues = computed(() => {
        const issues = [];
        const blocks = form.document?.blocks ?? [];
        const exerciseBlocks = blocks.filter((block) => block?.type === 'exercises');

        if (exerciseBlocks.length === 0) {
            issues.push('At least one Exercises Block is required.');
        }

        validateBlocks(blocks, issues, 'Activity');

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
