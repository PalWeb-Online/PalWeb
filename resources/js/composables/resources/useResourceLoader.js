import {ref} from "vue";
import {route} from "ziggy-js";

export function useResourceLoader({
                                         routeBase = null,
                                         routeParam = null,
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

    const getRouteUrl = (identifier, action, options = {}) => {
        if (!routeBase || !routeParam) {
            throw new Error('routeBase and routeParam are required for convention-based resource loading.');
        }

        return route(`api.${routeBase}.${action}`, {
            [routeParam]: identifier,
            ...(options.params ?? {}),
        });
    };

    const resolveUrl = (identifier, options = {}) => {
        return getUrl
            ? getUrl(identifier, options)
            : getRouteUrl(identifier, options.routeAction ?? 'show', options);
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
                resolveUrl(identifier, options),
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

    const fetchShowModel = (identifier, options = {}) => fetchModel(identifier, {
        ...options,
        routeAction: 'show',
    });

    const fetchEditModel = (identifier, options = {}) => fetchModel(identifier, {
        ...options,
        routeAction: 'edit',
    });

    return {
        model,
        notFound,
        isLoading,
        setModel,
        fetchShowModel,
        fetchEditModel,
    };
}
