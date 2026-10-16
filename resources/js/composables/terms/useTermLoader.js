import {computed} from "vue";
import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useTermLoader() {
    const {
        model: terms,
        notFound: termsNotFound,
        isLoading: isLoadingTerms,
        setModel: setTerms,
        fetchModel: fetchTerms,
    } = useResourceLoader({
        getUrl: (termIdentifier, options = {}) => route(options.routeName, {
            term: termIdentifier,
            ...(options.params ?? {}),
        }),
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
            },
        }),
        extractModel: (response) => response.data.terms ?? [],
    });

    const primaryTerm = computed(() => terms.value?.[0] ?? null);

    const fetchShowTerms = (termSlug, options = {}) => fetchTerms(termSlug, {
        ...options,
        routeName: 'api.terms.show',
    });

    const fetchEditTerms = (termId, options = {}) => fetchTerms(termId, {
        ...options,
        routeName: 'api.terms.edit',
    });

    return {
        terms,
        primaryTerm,
        termsNotFound,
        isLoadingTerms,
        setTerms,
        fetchTerms,
        fetchShowTerms,
        fetchEditTerms,
    };
}
