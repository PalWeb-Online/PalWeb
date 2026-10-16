import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useTermLoader} from "./useTermLoader.js";

export function useTermViewer() {
    const termLoader = useTermLoader();

    const {
        load: loadTerms,
        reload: reloadTerms,
    } = useResourceViewer({
        fetchModel: (termSlug, options = {}) => termLoader.fetchShowTerms(termSlug, {
            ...options,
        }),
        resetModel: termLoader.setTerms,
    });

    return {
        ...termLoader,
        loadTerms,
        reloadTerms,
    };
}
