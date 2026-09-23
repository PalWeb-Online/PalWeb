import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useDeckValidation({
                                      form,
                                      backendErrors,
                                  }) {
    const {t} = useI18n();

    const {
        isNonEmptyString,
        hasMaxLength,
        useValidationState,
    } = useResourceValidation();

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.name)) {
            errors.name = t('validation.required', {field: t('forms.fields.title')});

        } else if (!hasMaxLength(form.name, 50)) {
            errors.name = t('validation.max-chars', {field: t('forms.fields.title'), max: 50});
        }

        if (!hasMaxLength(form.description, 500)) {
            errors.description = t('validation.max-chars', {field: t('forms.fields.description'), max: 500});
        }

        const seenTermGlosses = new Set();

        (form.terms ?? []).forEach((term, index) => {
            const glossId = term.deckPivot?.gloss_id;

            if (!glossId) {
                errors[`terms.${index}.deckPivot.gloss_id`] = `${term.term}: ${t('validation.required', {field: t('gloss.key')})}`;
                return;
            }

            const key = `${term.id}:${glossId}`;

            if (seenTermGlosses.has(key)) {
                errors[`terms.${index}.deckPivot.gloss_id`] = `${term.term}: ${t('deck.validation.duplicate-term-gloss')}`;
            }

            seenTermGlosses.add(key);
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
        frontendErrors,
    };
}
