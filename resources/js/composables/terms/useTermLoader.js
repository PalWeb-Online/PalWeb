import {computed} from "vue";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useTermLoader() {
    const {
        model: terms,
        notFound: termsNotFound,
        isLoading: isLoadingTerms,
        setModel: setTerms,
        fetchShowModel: fetchShowTerms,
        fetchEditModel: fetchEditTerms,
        fetchRandomModel: fetchRandomTerms,
    } = useResourceLoader({
        routeBase: 'terms',
        routeParam: 'term',
        extractModel: (response) => response.data.terms ?? [],
    });

    const primaryTerm = computed(() => terms.value?.[0] ?? null);

    return {
        terms,
        primaryTerm,
        termsNotFound,
        isLoadingTerms,
        setTerms,
        fetchShowTerms,
        fetchEditTerms,
        fetchRandomTerms,
    };
}
