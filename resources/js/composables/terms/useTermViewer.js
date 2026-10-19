import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useTermLoader} from "./useTermLoader.js";

export function useTermViewer() {
    const termLoader = useTermLoader();

    const {
        load: loadTerms,
        reload: reloadTerms,
    } = useResourceViewer({
        fetchModel: termLoader.fetchShowTerms,
        resetModel: termLoader.setTerms,
    });

    return {
        ...termLoader,
        loadTerms,
        reloadTerms,
    };
}
