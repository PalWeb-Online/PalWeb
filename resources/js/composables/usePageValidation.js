import {computed} from "vue";
import {useDocumentValidation} from "./useDocumentValidation.js";

export function usePageValidation({
    form,
    page,
    descendantIds,
    allowedBlockTypes,
}) {
    const {
        isNonEmptyString,
        validateBlocks,
    } = useDocumentValidation({
        allowedBlockTypes,
        recursive: true,
    });

    const publishIssues = computed(() => {
        const issues = [];

        if (!isNonEmptyString(form.slug)) {
            issues.push('Slug is required.');
        }

        if (!isNonEmptyString(form.title)) {
            issues.push('Title is required.');
        }

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

    return {
        publishIssues,
        isPublishable,
    };
}
