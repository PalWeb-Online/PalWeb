import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";
import {useI18n} from "vue-i18n";

export function useUserValidation({
                                      userForm,
                                      userBackendErrors,
                                      teacherForm,
                                      teacherBackendErrors,
                                  }) {
    const {t} = useI18n();

    const {
        latinScriptPattern,
        arabicScriptPattern,
        isNonEmptyString,
        hasMaxLength,
        matchesPattern,
        mergeFieldErrors,
    } = useResourceValidation();

    const usernamePattern = /^[a-zA-Z0-9]+([._][a-zA-Z0-9]+)*$/;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    const userFrontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(userForm.name)) {
            errors.name = t('validation.required', {field: t('user.fields.name')});

        } else if (!hasMaxLength(userForm.name, 50)) {
            errors.name = t('validation.max-chars', {field: t('user.fields.name'), max: 50});

        } else if (!matchesPattern(userForm.name, latinScriptPattern)) {
            errors.name = t('validation.script.latin', {field: t('user.fields.name')});
        }

        if (!isNonEmptyString(userForm.username)) {
            errors.username = t('validation.required', {field: t('user.fields.username')});

        } else if (!hasMaxLength(userForm.username, 50)) {
            errors.username = t('validation.max-chars', {field: t('user.fields.username'), max: 50});

        } else if (!matchesPattern(userForm.username, usernamePattern)) {
            errors.username = t('user.validation.username-characters');
        }

        if (!isNonEmptyString(userForm.ar_name)) {
            errors.ar_name = t('validation.required', {field: t('user.fields.arabic-name')});

        } else if (!hasMaxLength(userForm.ar_name, 50)) {
            errors.ar_name = t('validation.max-chars', {field: t('user.fields.arabic-name'), max: 50});

        } else if (!matchesPattern(userForm.ar_name, arabicScriptPattern)) {
            errors.ar_name = t('validation.script.arabic', {field: t('user.fields.arabic-name')});
        }

        if (!hasMaxLength(userForm.home, 100)) {
            errors.home = t('validation.max-chars', {field: t('user.fields.home'), max: 100});
        }

        if (!hasMaxLength(userForm.bio, 500)) {
            errors.bio = t('validation.max-chars', {field: t('user.fields.bio'), max: 500});
        }

        return errors;
    });

    const teacherFrontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(teacherForm.email)) {
            errors.email = t('validation.required', {field: t('teacher.fields.email')});

        } else if (!matchesPattern(teacherForm.email, emailPattern)) {
            errors.email = t('validation.email');

        } else if (!hasMaxLength(teacherForm.email, 255)) {
            errors.email = t('validation.max-chars', {field: t('teacher.fields.email'), max: 255});
        }

        if (!hasMaxLength(teacherForm.bio, 5000)) {
            errors.bio = t('validation.max-chars', {field: t('teacher.fields.bio'), max: 5000});
        }

        return errors;
    });

    const userErrors = computed(() => {
        return mergeFieldErrors(userFrontendErrors.value, userBackendErrors.value);
    });

    const teacherErrors = computed(() => {
        return mergeFieldErrors(teacherFrontendErrors.value, teacherBackendErrors.value);
    });

    const isValidUserRequest = computed(() => {
        return Object.keys(userFrontendErrors.value).length === 0;
    });

    const isValidTeacherRequest = computed(() => {
        return Object.keys(teacherFrontendErrors.value).length === 0;
    });

    return {
        userFrontendErrors,
        teacherFrontendErrors,
        userErrors,
        teacherErrors,
        isValidUserRequest,
        isValidTeacherRequest,
    };
}
