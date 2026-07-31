import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useUnitValidation({
                                      form,
                                      backendErrors,
                                      allowedBlockTypes,
                                  }) {
    const {t} = useI18n();

    const {
        isNonEmptyString,
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

        (form.lessons ?? []).forEach((lesson, index) => {
            const lessonIndex = t('lesson.key-index', {index: `${form.position}0${index + 1}`});

            if (!lesson.title) {
                errors[`lesson.${index}.title`] = `${lessonIndex}: ${t('validation.required', {field: t('forms.fields.title')})}`;
            }
        });

        return errors;
    });

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
    };
}
