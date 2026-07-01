import {ref} from "vue";

export function useResourceLoader({
                                         getUrl,
                                         getRequestConfig = () => ({}),
                                         extractModel,
                                         extractMeta = () => ({}),
                                         onSetModel = null,
                                     }) {
    const model = ref(null);
    const notFound = ref(false);
    const isLoading = ref(false);

    const setModel = (value = null, meta = {}) => {
        model.value = value;
        notFound.value = false;

        onSetModel?.(meta);
    };

    const fetchModel = async (identifier, options = {}) => {
        if (identifier === null || identifier === undefined || identifier === '') {
            setModel();
            return null;
        }

        isLoading.value = true;
        notFound.value = false;

        try {
            const response = await axios.get(
                getUrl(identifier, options),
                getRequestConfig(options),
            );

            setModel(
                extractModel(response),
                extractMeta(response),
            );

            return model.value;

        } catch (error) {
            if (error?.response?.status === 404) {
                setModel();
                notFound.value = true;
                return null;
            }

            throw error;

        } finally {
            isLoading.value = false;
        }
    };

    return {
        model,
        notFound,
        isLoading,
        setModel,
        fetchModel,
    };
}
