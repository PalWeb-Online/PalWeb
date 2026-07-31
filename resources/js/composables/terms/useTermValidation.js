import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useTermValidation({
                                      form,
                                      backendErrors,
                                  }) {
    const {t} = useI18n();

    const {
        latinScriptPattern,
        arabicScriptPattern,
        isNonEmptyString,
        matchesPattern,
        useValidationState,
    } = useResourceValidation();

    const glossRelativeTypes = ['synonym', 'antonym', 'isPatient', 'noPatient', 'hasObject'];

    const isEmpty = (value) => value === null || value === undefined || value === '';

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.term)) {
            errors.term = t('validation.required', {field: t('term.key')});

        } else if (!matchesPattern(form.term, arabicScriptPattern)) {
            errors.term = t('validation.script.arabic', {field: t('term.key')});
        }

        if (!isNonEmptyString(form.category)) {
            errors.category = t('validation.required', {field: t('term.fields.category')});
        }

        (form.pronunciations ?? []).forEach((pronunciation, index) => {
            if (!isNonEmptyString(pronunciation.translit)) {
                errors[`pronunciations.${index}.translit`] = t('validation.required', {field: t('pronunciation.fields.transcription')});

            } else if (!matchesPattern(pronunciation.translit, latinScriptPattern)) {
                errors[`pronunciations.${index}.translit`] = t('validation.script.latin', {field: t('pronunciation.fields.transcription')});
            }

            if (!isNonEmptyString(pronunciation.phonemic)) {
                errors[`pronunciations.${index}.phonemic`] = t('validation.required', {field: t('pronunciation.fields.phonemic')});
            }

            if (!isNonEmptyString(pronunciation.phonetic)) {
                errors[`pronunciations.${index}.phonetic`] = t('validation.required', {field: t('pronunciation.fields.phonetic')});
            }

            if (!pronunciation.dialect_id) {
                errors[`pronunciations.${index}.dialect_id`] = t('validation.min-items', {
                    item: t('dialect.key'),
                    min: 1
                });
            }
        });

        if (!isEmpty(form.root?.root)) {
            if (form.root.root.length < 3) {
                errors['root.root'] = t('validation.min-chars', {field: t('term.fields.root'), min: 3});

            } else if (form.root.root.length > 4) {
                errors['root.root'] = t('validation.max-chars', {field: t('term.fields.root'), max: 4});

            } else if (!matchesPattern(form.root.root, arabicScriptPattern)) {
                errors['root.root'] = t('validation.script.arabic', {field: t('term.fields.root')});
            }
        }

        if (!isNonEmptyString(form.etymology?.type)) {
            errors['etymology.type'] = t('validation.required', {field: t('term.data.type')});
        }

        (form.attributes ?? []).forEach((attribute, index) => {
            if (!isNonEmptyString(attribute.attribute)) {
                errors[`attributes.${index}.attribute`] = t('validation.required', {field: t('term.fields.attribute')});
            }
        });

        (form.spellings ?? []).forEach((spelling, index) => {
            if (!isNonEmptyString(spelling.spelling)) {
                errors[`spellings.${index}.spelling`] = t('validation.required', {field: t('term.fields.spelling')});

            } else if (!matchesPattern(spelling.spelling, arabicScriptPattern)) {
                errors[`spellings.${index}.spelling`] = t('validation.script.arabic', {field: t('term.fields.spelling')});
            }
        });

        (form.relatives ?? []).forEach((relative, index) => {
            if (!isNonEmptyString(relative.slug)) {
                errors[`relatives.${index}.slug`] = t('validation.required', {field: t('forms.fields.slug')});
            }

            if (!isNonEmptyString(relative.type)) {
                errors[`relatives.${index}.type`] = t('validation.required', {field: t('forms.fields.type')});
            }

            if (glossRelativeTypes.includes(relative.type) && isEmpty(relative.gloss_id)) {
                errors[`relatives.${index}.gloss_id`] = t('term.validation.relative-gloss');
            }
        });

        (form.glosses ?? []).forEach((gloss, glossIndex) => {
            if (!isNonEmptyString(gloss.gloss)) {
                errors[`glosses.${glossIndex}.gloss`] = t('validation.required', {field: t('gloss.key')});
            }

            (gloss.attributes ?? []).forEach((attribute, attributeIndex) => {
                if (!isNonEmptyString(attribute.attribute)) {
                    errors[`glosses.${glossIndex}.attributes.${attributeIndex}.attribute`] = t('validation.required', {field: t('term.fields.attribute')});
                }
            });
        });

        (form.inflections ?? []).forEach((inflection, index) => {
            if (!isNonEmptyString(inflection.form)) {
                errors[`inflections.${index}.form`] = t('validation.required', {field: t('inflection.fields.form')});
            }

            if (!isNonEmptyString(inflection.inflection)) {
                errors[`inflections.${index}.inflection`] = t('validation.required', {field: t('inflection.key')});

            } else if (!matchesPattern(inflection.inflection, arabicScriptPattern)) {
                errors[`inflections.${index}.inflection`] = t('validation.script.arabic', {field: t('inflection.key')});
            }

            if (!isNonEmptyString(inflection.translit)) {
                errors[`inflections.${index}.translit`] = t('validation.required', {field: t('inflection.fields.transcription')});

            } else if (!matchesPattern(inflection.translit, latinScriptPattern)) {
                errors[`inflections.${index}.translit`] = t('validation.script.latin', {field: t('inflection.fields.transcription')});
            }
        });

        return errors;
    });

    const confirmableIssues = computed(() => {
        const messages = [];

        if (form.category === 'verb') {
            if (isEmpty(form.root?.root)) {
                messages.push(t('term.validation.verb-missing-root'));
            }

            if (!form.attributes.some(attribute => attribute.attribute === 'idiom') && !form.patterns.find(pattern => pattern.type === 'verbal')) {
                messages.push(t('term.validation.verb-missing-pattern'));
            }

            if (form.glosses.some(gloss => !gloss.attributes.length)) {
                messages.push(t('term.validation.verb-missing-gloss-attribute'));
            }
        }

        if (form.category === 'noun' && !form.attributes.some(attribute => ['masculine', 'feminine', 'plural'].includes(attribute.attribute))) {
            messages.push(t('term.validation.noun-missing-gender'));
        }

        if (form.category === 'adjective' && !form.inflections.length) {
            messages.push(t('term.validation.adjective-missing-inflections'));
        }

        if (form.attributes.some(attribute => attribute.attribute === 'idiom') && !form.relatives.filter(relative => relative.type === 'component').length) {
            messages.push(t('term.validation.idiom-missing-components'));
        }

        return messages;
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
        confirmableIssues,
    };
}
