import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";

export function useSentenceValidation({
                                          form,
                                          backendErrors,
                                      }) {
    const {
        isNonEmptyString,
        matchesPattern,
        mergeFieldErrors,
    } = useResourceValidation();

    const latinScriptPattern = /^[\p{Script=Latin}\s-]+$/u;

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.trans)) {
            errors.trans = 'Translation is required.';
        }

        if (!Array.isArray(form.terms) || form.terms.length === 0) {
            errors.terms = 'At least one Term is required.';
        }

        (form.terms ?? []).forEach((term, index) => {
            const termName = `Term ${index + 1}`;

            if (!isNonEmptyString(term.sentencePivot?.sent_term)) {
                errors[`terms.${index}.sentencePivot.sent_term`] = `${termName}: Sentence term is required.`;
            }

            if (!isNonEmptyString(term.sentencePivot?.sent_translit)) {
                errors[`terms.${index}.sentencePivot.sent_translit`] = `${termName}: Sentence transcription is required.`;

            } else if (!matchesPattern(term.sentencePivot.sent_translit, latinScriptPattern)) {
                errors[`terms.${index}.sentencePivot.sent_translit`] = `${termName}: Transcription may only contain Latin-script characters.`;
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
