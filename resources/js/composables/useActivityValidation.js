import {computed} from "vue";
import {useDocumentValidation} from "./useDocumentValidation.js";

export function useActivityValidation({
                                          form,
                                          allowedBlockTypes,
                                      }) {
    const {
        validateBlocks,
    } = useDocumentValidation({
        allowedBlockTypes,
        recursive: false,
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

    return {
        publishIssues,
        isPublishable,
    };
}
