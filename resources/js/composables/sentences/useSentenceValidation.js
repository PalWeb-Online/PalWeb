import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useSentenceValidation({
                                          form,
                                          backendErrors,
                                      }) {
    const {t} = useI18n();

    const {
        latinScriptPattern,
        isNonEmptyString,
        matchesPattern,
        useValidationState,
    } = useResourceValidation();

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.trans)) {
            errors.trans = t('validation.required', {field: t('sentence.fields.translation')});
        }

        if (!Array.isArray(form.terms) || form.terms.length === 0) {
            errors.terms = t('validation.min-items', {item: t('term.key'), min: 1});
        }

        (form.terms ?? []).forEach((term, index) => {
            const termName = `${t('term.key')} ${index + 1}`;

            if (!isNonEmptyString(term.sentencePivot?.sent_term)) {
                errors[`terms.${index}.sentencePivot.sent_term`] = `${termName}: ${t('validation.required', {field: t('term.key')})}`;
            }

            if (!isNonEmptyString(term.sentencePivot?.sent_translit)) {
                errors[`terms.${index}.sentencePivot.sent_translit`] = `${termName}: ${t('validation.required', {field: t('sentence.fields.transcription')})}`;

            } else if (!matchesPattern(term.sentencePivot.sent_translit, latinScriptPattern)) {
                errors[`terms.${index}.sentencePivot.sent_translit`] = `${termName}: ${t('validation.script.latin', {field: t('sentence.fields.transcription')})}`;
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
