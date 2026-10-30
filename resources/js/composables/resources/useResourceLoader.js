import {ref} from 'vue';
import {apiUrl, pathSegment} from '../../utils/apiUrl.js';
import {useRouter} from 'vue-router';

export function useResourceLoader({
                                      routeBase = null,
                                      routeParam = null,
                                      getUrl,
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

        router.replace({
            name: 'error',
            query: {
                status: error?.response?.status,
            },
        });

        if (error.response?.status === 404) {
            notFound.value = true;
        }

        return null;
    };

    const getRouteUrl = (identifier, action) => {
        if (!routeBase || !routeParam) {
            throw new Error('routeBase and routeParam are required for convention-based resource loading.');
        }

        const actionPath = action === 'edit' ? '/edit' : '';

        return apiUrl(`${routeBase}/${pathSegment(identifier)}${actionPath}`);
    };

    const getRandomRouteUrl = () => {
        if (!routeBase) {
            throw new Error('routeBase is required for convention-based random resource loading.');
        }

        return apiUrl(`${routeBase}/random`);
    };

    const resolveUrl = (identifier, action) => {
        return getUrl
            ? getUrl(identifier)
            : getRouteUrl(identifier, action);
    };

    const fetchModel = async (identifier, action = 'show', options = {}) => {
        if (identifier === null || identifier === undefined || identifier === '') {
            setModel();
            return null;
        }

        isLoading.value = true;
        notFound.value = false;

        try {
            const response = await axios.get(
                resolveUrl(identifier, action),
                options,
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
                getRandomRouteUrl(),
                options,
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

    const fetchShowModel = (identifier, options = {}) => fetchModel(identifier, 'show', options);
    const fetchEditModel = (identifier, options = {}) => fetchModel(identifier, 'edit', options);

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
