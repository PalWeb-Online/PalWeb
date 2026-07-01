import {computed} from "vue";
import {useResourceValidation} from "../resources/useResourceValidation.js";

export function useUserValidation({
                                      userForm,
                                      userBackendErrors,
                                      teacherForm,
                                      teacherBackendErrors,
                                  }) {
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
            errors.name = 'You must have a name.';

        } else if (!hasMaxLength(userForm.name, 50)) {
            errors.name = 'Your name must not be greater than 50 characters.';

        } else if (!matchesPattern(userForm.name, latinScriptPattern)) {
            errors.name = 'Your name may only contain Latin-script letters & hyphens.';
        }

        if (!isNonEmptyString(userForm.username)) {
            errors.username = 'You must have a username.';

        } else if (!hasMaxLength(userForm.username, 50)) {
            errors.username = 'Your username must not be greater than 50 characters.';

        } else if (!matchesPattern(userForm.username, usernamePattern)) {
            errors.username = 'Your username has invalid characters.';
        }

        if (!isNonEmptyString(userForm.ar_name)) {
            errors.ar_name = 'You must have an Arabic name.';

        } else if (!hasMaxLength(userForm.ar_name, 50)) {
            errors.ar_name = 'Your Arabic name must not be greater than 50 characters.';

        } else if (!matchesPattern(userForm.ar_name, arabicScriptPattern)) {
            errors.ar_name = 'Your Arabic name may only contain Arabic-script characters.';
        }

        if (!hasMaxLength(userForm.home, 100)) {
            errors.home = 'The Home field must not be greater than 100 characters.';
        }

        if (!hasMaxLength(userForm.bio, 500)) {
            errors.bio = 'The Bio field must not be greater than 500 characters.';
        }

        return errors;
    });

    const teacherFrontendErrors = computed(() => {
        const errors = {};

        if (!isNonEmptyString(teacherForm.email)) {
            errors.email = 'You must add a contact email.';

        } else if (!matchesPattern(teacherForm.email, emailPattern)) {
            errors.email = 'Your contact email must be a valid email address.';

        } else if (!hasMaxLength(teacherForm.email, 255)) {
            errors.email = 'Your contact email must not be greater than 255 characters.';
        }

        if (!hasMaxLength(teacherForm.bio, 5000)) {
            errors.bio = 'The Bio field must not be greater than 5000 characters.';
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
