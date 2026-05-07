import {usePageLoader} from "./usePageLoader.js";
import {useDocumentResourceViewer} from "../documents/useDocumentResourceViewer.js";

export function usePageViewer() {
    const pageLoader = usePageLoader();

    const {
        load: loadPage,
        reload: reloadPage,
    } = useDocumentResourceViewer({
        fetchModel: pageLoader.fetchPage,
        resetModel: pageLoader.setPage,
        getBlocks: (document) => document?.blocks ?? [],
    });

    return {
        ...pageLoader,
        loadPage,
        reloadPage,
    };
}
