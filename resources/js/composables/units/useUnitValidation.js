import {computed} from "vue";
import {useDocumentResourceValidation} from "../documents/useDocumentResourceValidation.js";

export function useUnitValidation({
                                          form,
                                          backendErrors,
                                          allowedBlockTypes,
                                      }) {
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
            errors.title = 'Title is required.';
        }

        (form.lessons ?? []).forEach((lesson, index) => {
            const lessonIndex = `Lesson ${form.position}0${index + 1}`;

            if (!lesson.title) {
                errors[`lesson.${index}.title`] = `${lessonIndex}: Title is required.`;
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
