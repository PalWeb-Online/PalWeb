import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";

export function useTermValidation({
                                      form,
                                      backendErrors,
                                  }) {
    const {
        isNonEmptyString,
        matchesPattern,
        mergeFieldErrors,
    } = useResourceValidation();

    const arabicScriptPattern = /^[\p{scx=Arabic}\s]+$/u;
    const latinScriptPattern = /^[\p{scx=Latin}\s-]+$/u;

    const glossRelativeTypes = ['synonym', 'antonym', 'isPatient', 'noPatient', 'hasObject'];

    const isEmpty = (value) => value === null || value === undefined || value === '';

    const frontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(form.term)) {
            errors.term = 'Term is required.';

        } else if (!matchesPattern(form.term, arabicScriptPattern)) {
            errors.term = 'Term may only contain Arabic-script characters.';
        }

        if (!isNonEmptyString(form.category)) {
            errors.category = 'Category is required.';
        }

        (form.pronunciations ?? []).forEach((pronunciation, index) => {
            if (!isNonEmptyString(pronunciation.translit)) {
                errors[`pronunciations.${index}.translit`] = 'Transcription is required.';

            } else if (!matchesPattern(pronunciation.translit, latinScriptPattern)) {
                errors[`pronunciations.${index}.translit`] = 'Transcription may only contain Latin-script characters.';
            }

            if (!isNonEmptyString(pronunciation.phonemic)) {
                errors[`pronunciations.${index}.phonemic`] = 'Phonemic transcription is required.';
            }

            if (!isNonEmptyString(pronunciation.phonetic)) {
                errors[`pronunciations.${index}.phonetic`] = 'Phonetic transcription is required.';
            }

            if (!pronunciation.dialect_id) {
                errors[`pronunciations.${index}.dialect_id`] = 'Dialect is required.';
            }
        });

        if (!isEmpty(form.root?.root)) {
            if (form.root.root.length < 3) {
                errors['root.root'] = 'Root must be at least 3 characters.';

            } else if (form.root.root.length > 4) {
                errors['root.root'] = 'Root must not be greater than 4 characters.';

            } else if (!matchesPattern(form.root.root, arabicScriptPattern)) {
                errors['root.root'] = 'Root may only contain Arabic-script characters.';
            }
        }

        if (!isNonEmptyString(form.etymology?.type)) {
            errors['etymology.type'] = 'Etymology type is required.';
        }

        (form.attributes ?? []).forEach((attribute, index) => {
            if (!isNonEmptyString(attribute.attribute)) {
                errors[`attributes.${index}.attribute`] = 'Attribute is required.';
            }
        });

        (form.spellings ?? []).forEach((spelling, index) => {
            if (!isNonEmptyString(spelling.spelling)) {
                errors[`spellings.${index}.spelling`] = 'Spelling is required.';

            } else if (!matchesPattern(spelling.spelling, arabicScriptPattern)) {
                errors[`spellings.${index}.spelling`] = 'Spelling may only contain Arabic-script characters.';
            }
        });

        (form.relatives ?? []).forEach((relative, index) => {
            if (!isNonEmptyString(relative.slug)) {
                errors[`relatives.${index}.slug`] = 'Relative is required.';
            }

            if (!isNonEmptyString(relative.type)) {
                errors[`relatives.${index}.type`] = 'Relationship type is required.';
            }

            if (glossRelativeTypes.includes(relative.type) && isEmpty(relative.gloss_id)) {
                errors[`relatives.${index}.gloss_id`] = 'Gloss is required for this relationship type.';
            }
        });

        (form.glosses ?? []).forEach((gloss, glossIndex) => {
            if (!isNonEmptyString(gloss.gloss)) {
                errors[`glosses.${glossIndex}.gloss`] = 'Gloss is required.';
            }

            (gloss.attributes ?? []).forEach((attribute, attributeIndex) => {
                if (!isNonEmptyString(attribute.attribute)) {
                    errors[`glosses.${glossIndex}.attributes.${attributeIndex}.attribute`] = 'Gloss attribute is required.';
                }
            });
        });

        (form.inflections ?? []).forEach((inflection, index) => {
            if (!isNonEmptyString(inflection.form)) {
                errors[`inflections.${index}.form`] = 'Inflection form is required.';
            }

            if (!isNonEmptyString(inflection.inflection)) {
                errors[`inflections.${index}.inflection`] = 'Inflection is required.';

            } else if (!matchesPattern(inflection.inflection, arabicScriptPattern)) {
                errors[`inflections.${index}.inflection`] = 'Inflection may only contain Arabic-script characters.';
            }

            if (!isNonEmptyString(inflection.translit)) {
                errors[`inflections.${index}.translit`] = 'Inflection transcription is required.';

            } else if (!matchesPattern(inflection.translit, latinScriptPattern)) {
                errors[`inflections.${index}.translit`] = 'Inflection transcription may only contain Latin-script letters & hyphens.';
            }
        });

        return errors;
    });

    const isValidRequest = computed(() => {
        return Object.keys(frontendErrors.value).length === 0;
    });

    const validationErrors = computed(() => {
        return mergeFieldErrors(frontendErrors.value, backendErrors?.value ?? {});
    });

    const confirmableIssues = computed(() => {
        const messages = [];

        if (form.category === 'verb') {
            if (isEmpty(form.root?.root)) {
                messages.push('The Verb has no root.');
            }

            if (!form.attributes.some(attribute => attribute.attribute === 'idiom') && !form.patterns.find(pattern => pattern.type === 'verbal')) {
                messages.push('The Verb has no pattern.');
            }

            if (form.glosses.some(gloss => !gloss.attributes.length)) {
                messages.push('The Verb has a Gloss with no Attribute.');
            }
        }

        if (form.category === 'noun' && !form.attributes.some(attribute => ['masculine', 'feminine', 'plural'].includes(attribute.attribute))) {
            messages.push('The Noun has no gender.');
        }

        if (form.category === 'adjective' && !form.inflections.length) {
            messages.push('The Adjective has no Inflections.');
        }

        if (form.attributes.some(attribute => attribute.attribute === 'idiom') && !form.relatives.filter(relative => relative.type === 'component').length) {
            messages.push('The Idiom has no components.');
        }

        return messages;
    });

    return {
        isValidRequest,
        validationErrors,
        confirmableIssues,
    };
}
