import {ref} from 'vue';
import {apiUrl, pathSegment} from '../../utils/apiUrl.js';
import {useRouter} from 'vue-router';

export function useResourceLoader({
                                      routeBase = null,
                                      routeParam = null,
                                      getUrl,
                                      getRequestConfig = () => ({}),
                                      extractModel,
                                      extractMeta = () => ({}),
                                      onSetModel = null,
                                  }) {
    const router = useRouter();
    const model = ref(null);
    const notFound = ref(false);
    const isLoading = ref(false);

    const setModel = (value = null, meta = {}) => {
        model.value = value;
        notFound.value = false;

        onSetModel?.(meta);
    };

    const setError = (error) => {
        if (!error?.isAxiosError && !error?.response && !error?.request) {
            throw error;
        }

        setModel();

        if (error.response?.status === 404) {
            notFound.value = true;
        }

        router.replace({
            name: 'error',
            query: {
                status: error?.response?.status,
            },
        });

        return null;
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

    const getRandomRouteUrl = (options = {}) => {
        if (!routeBase) {
            throw new Error('routeBase is required for convention-based random resource loading.');
        }

        return route(`api.${routeBase}.random`, options.params ?? {});
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
            return setError(error);

        } finally {
            isLoading.value = false;
        }
    };

    const fetchRandomModel = async (options = {}) => {
        isLoading.value = true;
        notFound.value = false;

        try {
            const response = await axios.get(
                getRandomRouteUrl(options),
                getRequestConfig(options),
            );

            setModel(
                extractModel(response),
                extractMeta(response),
            );

            return model.value;

        } catch (error) {
            return setError(error);

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
        fetchRandomModel,
    };
}
