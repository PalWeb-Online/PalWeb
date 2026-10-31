import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useTermLoader} from "./useTermLoader.js";

export function useTermViewer() {
    const termLoader = useTermLoader();

    const {
        load: loadTerms,
        loadRandom: loadRandomTerms,
        reload: reloadTerms,
        reloadRandom: reloadRandomTerms,
    } = useResourceViewer({
        fetchModel: termLoader.fetchShowTerms,
        fetchRandomModel: termLoader.fetchRandomTerms,
        resetModel: termLoader.setTerms,
    });

    return {
        ...termLoader,
        loadTerms,
        loadRandomTerms,
        reloadTerms,
        reloadRandomTerms,
    };
}
