import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useDialogValidation({
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

        if (!isNonEmptyString(form.title)) {
            errors.title = t('validation.required', {field: t('forms.fields.title')});

        } else if (!hasMaxLength(form.name, 50)) {
            errors.name = t('validation.max-chars', {field: t('forms.fields.title'), max: 50});
        }

        (form.sentences ?? []).forEach((sentence, index) => {
            const sentenceIndex = `${t('actions.models.sentence')} ${index + 1}`;

            if (!isNonEmptyString(sentence.speaker)) {
                errors[`sentence.${index}.speaker`] = `${sentenceIndex}: ${t('validation.required', {field: t('sentence.fields.speaker')})}`;

            } else if (!sentence.position) {
                errors[`sentence.${index}.position`] = `${sentenceIndex}: ${t('validation.required', {field: t('sentence.fields.position')})}`;
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
