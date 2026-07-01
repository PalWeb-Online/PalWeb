import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";

export function useDialogValidation({
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

        if (!isNonEmptyString(form.title)) {
            errors.title = 'Title is required.';

        } else if (!hasMaxLength(form.name, 50)) {
            errors.name = 'Title must not be greater than 50 characters.';
        }

        (form.sentences ?? []).forEach((sentence, index) => {
            const sentenceIndex = `Sentence ${index + 1}`;

            if (!isNonEmptyString(sentence.speaker)) {
                errors[`sentence.${index}.speaker`] = `${sentenceIndex}: Sentence speaker is required.`;

            } else if (!sentence.position) {
                errors[`sentence.${index}.position`] = `${sentenceIndex}: Sentence position is required.`;
            }
        });

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
