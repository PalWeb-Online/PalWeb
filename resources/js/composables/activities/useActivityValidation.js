import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useActivityValidation({
                                          form,
                                          backendErrors,
                                          allowedBlockTypes,
                                      }) {
    const {t} = useI18n();

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
            errors.title = t('validation.required', {field: t('forms.fields.title')});
        }

        return errors;
    });

    const publishIssues = computed(() => {
        const issues = [];
        const blocks = form.document?.blocks ?? [];
        const exerciseBlocks = blocks.filter((block) => block?.type === 'exercises');

        if (exerciseBlocks.length === 0) {
            issues.push(t('validation.min-items', {item: 'Exercises Block', min: '1'}));
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
