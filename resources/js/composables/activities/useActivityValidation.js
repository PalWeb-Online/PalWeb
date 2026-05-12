import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";

export function useActivityValidation({
                                          form,
                                          allowedBlockTypes,
                                      }) {
    const {
        isNonEmptyString,
        validateBlocks,
    } = useDocumentResourceValidation({
        allowedBlockTypes,
        recursive: false,
    });

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
        const blocks = form.document?.blocks ?? [];
        const exerciseBlocks = blocks.filter((block) => block?.type === 'exercises');

        if (exerciseBlocks.length === 0) {
            issues.push('At least one Exercises Block is required.');
        }

        validateBlocks(blocks, issues, 'Activity');

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
