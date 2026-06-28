import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";

export function useDeckValidation({
                                      form,
                                      backendErrors,
                                  }) {
    const {
        isNonEmptyString,
        hasMaxLength,
        mergeFieldErrors,
    } = useResourceValidation();

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.name)) {
            errors.name = 'Title is required.';

        } else if (!hasMaxLength(form.name, 50)) {
            errors.name = 'Title must not be greater than 50 characters.';
        }

        if (!hasMaxLength(form.description, 500)) {
            errors.description = 'Description must not be greater than 500 characters.';
        }

        return errors;
    });

    const isValidRequest = computed(() => Object.keys(frontendErrors.value).length === 0);

    const validationErrors = computed(() => {
        return mergeFieldErrors(frontendErrors.value, backendErrors?.value ?? {});
    });

    return {
        isValidRequest,
        validationErrors,
    };
}
