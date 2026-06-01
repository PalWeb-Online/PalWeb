export function useResourceValidation() {
    const isString = (value) => {
        return typeof value === 'string';
    };

    const isNonEmptyString = (value) => {
        return isString(value) && value.trim().length > 0;
    };

    const hasMaxLength = (value, max) => {
        return !isString(value) || value.length <= max;
    };

    const matchesPattern = (value, pattern) => {
        return !isString(value) || pattern.test(value);
    };

    const mergeFieldErrors = (frontendErrors = {}, backendErrors = {}) => {
        return {
            ...(backendErrors ?? {}),
            ...(frontendErrors ?? {}),
        };
    };

    return {
        isString,
        isNonEmptyString,
        hasMaxLength,
        matchesPattern,
        mergeFieldErrors,
    };
}
