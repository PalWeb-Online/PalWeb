import {computed, unref} from "vue";

export function useResourceValidation() {
    const latinScriptPattern = /^[\p{scx=Latin}\s-]+$/u;
    const arabicScriptPattern = /^[\p{scx=Arabic}\s]+$/u;

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

    const formatFieldName = (field) => {
        return String(field)
            .split('.')
            .map((part) => {
                if (/^\d+$/.test(part)) {
                    return String(Number(part) + 1);
                }

                return part
                    .replaceAll('_', ' ')
                    .replace(/([a-z])([A-Z])/g, '$1 $2')
                    .replace(/\b\w/g, (char) => char.toUpperCase());
            })
            .join(' ');
    };

    const prefixFieldError = (field, message) => {
        const label = formatFieldName(field);

        if (!message || !label) {
            return message;
        }

        if (message.startsWith(`${label}:`)) {
            return message;
        }

        return `${label}: ${message}`;
    };

    const normalizeBackendErrors = (backendErrors = {}) => {
        return Object.fromEntries(
            Object.entries(backendErrors ?? {}).map(([field, message]) => {
                return [field, prefixFieldError(field, message)];
            }),
        );
    };

    const mergeFieldErrors = (frontendErrors = {}, backendErrors = {}) => {
        return {
            ...normalizeBackendErrors(backendErrors),
            ...(frontendErrors ?? {}),
        };
    };

    const useValidationState = ({
                                       frontendErrors,
                                       backendErrors,
                                   }) => {
        const isValidRequest = computed(() => {
            return Object.keys(unref(frontendErrors) ?? {}).length === 0;
        });

        const validationErrors = computed(() => {
            return mergeFieldErrors(
                unref(frontendErrors) ?? {},
                unref(backendErrors) ?? {},
            );
        });

        return {
            isValidRequest,
            validationErrors,
        };
    };

    return {
        latinScriptPattern,
        arabicScriptPattern,
        isString,
        isNonEmptyString,
        hasMaxLength,
        matchesPattern,
        mergeFieldErrors,
        useValidationState,
    };
}
