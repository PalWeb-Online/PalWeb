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
        getUrl: (termId, options = {}) => route(options.routeName ?? 'api.terms.fetch', {
            term: termId,
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

    return {
        terms,
        primaryTerm,
        termsNotFound,
        isLoadingTerms,
        setTerms,
        fetchTerms,
    };
}
