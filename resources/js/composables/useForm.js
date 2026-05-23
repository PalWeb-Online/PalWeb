import {computed, reactive, ref, toRaw} from "vue";
import isEqual from "lodash/isEqual";
import cloneDeep from "lodash/cloneDeep";

export function useForm(initialValues = {}) {
    const form = reactive(cloneDeep(initialValues));
    const original = ref(cloneDeep(initialValues));
    const errors = ref({});
    const processing = ref(false);
    const recentlySuccessful = ref(false);

    let recentlySuccessfulTimeout = null;

    const payload = () => {
        return cloneDeep(form);
    };

    const isDirty = computed(() => {
        return !isEqual(payload(), original.value);
    });

    const setErrors = (newErrors = {}) => {
        errors.value = newErrors ?? {};
    };

    const clearErrors = (...fields) => {
        if (fields.length === 0) {
            errors.value = {};
            return;
        }

        const nextErrors = {...errors.value};

        fields.forEach((field) => {
            delete nextErrors[field];
        });

        errors.value = nextErrors;
    };

    const defaults = (values = null) => {
        original.value = cloneDeep(values ?? payload());
    };

    const reset = (...fields) => {
        const source = cloneDeep(original.value);

        if (fields.length === 0) {
            Object.keys(form).forEach((key) => {
                delete form[key];
            });

            Object.assign(form, source);
            return;
        }

        fields.forEach((field) => {
            form[field] = cloneDeep(source[field]);
        });
    };

    const setRecentlySuccessful = () => {
        recentlySuccessful.value = true;

        if (recentlySuccessfulTimeout) {
            clearTimeout(recentlySuccessfulTimeout);
        }

        recentlySuccessfulTimeout = setTimeout(() => {
            recentlySuccessful.value = false;
        }, 2000);
    };

    return {
        form,
        errors,
        isDirty,
        processing,
        recentlySuccessful,
        reset,
        payload,
        defaults,
        setErrors,
        clearErrors,
        setRecentlySuccessful,
    };
}
