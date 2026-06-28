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

    const validationErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.trans)) {
            errors.trans = 'Translation is required.';
        }

        if (!Array.isArray(form.terms) || form.terms.length === 0) {
            errors.terms = 'At least one Term is required.';
        }

        (form.terms ?? []).forEach((term, index) => {
            if (!isNonEmptyString(term.sentencePivot?.sent_term)) {
                errors[`terms.${index}.sentencePivot.sent_term`] = 'Sentence term is required.';
            }

            if (!isNonEmptyString(term.sentencePivot?.sent_translit)) {
                errors[`terms.${index}.sentencePivot.sent_translit`] = 'Sentence transliteration is required.';

            } else if (!matchesPattern(term.sentencePivot.sent_translit, latinScriptPattern)) {
                errors[`terms.${index}.sentencePivot.sent_translit`] = 'Transcription may only contain Latin-script characters.';
            }
        });

        return errors;
    });

    const errors = computed(() => {
        return mergeFieldErrors(validationErrors.value, backendErrors?.value ?? {});
    });

    const isValidRequest = computed(() => Object.keys(validationErrors.value).length === 0);

    return {
        errors,
        isValidRequest,
    };
}
