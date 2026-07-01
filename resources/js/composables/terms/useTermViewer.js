import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useTermLoader} from "./useTermLoader.js";

export function useTermViewer() {
    const termLoader = useTermLoader();

    const {
        load: loadTerms,
        reload: reloadTerms,
    } = useResourceViewer({
        fetchModel: (termId, options = {}) => termLoader.fetchTerms(termId, {
            include: 'show',
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
