export function useValidationErrors({setErrors} = {}) {
    const handleValidationError = (error) => {
        if (error?.response?.status === 422) {
            setErrors?.(error.response.data.errors ?? {});
            return true;
        }

        return false;
    };

    return {
        handleValidationError,
    };
}
