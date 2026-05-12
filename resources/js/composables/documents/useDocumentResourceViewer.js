import {useDocumentResourceLoader} from "./useDocumentResourceLoader.js";
import {useResourceViewer} from "../resources/useResourceViewer.js";

export function useDocumentResourceViewer({
                                              fetchModel,
                                              resetModel,
                                              getBlocks,
                                          }) {
    const documentLoader = useDocumentResourceLoader();

    const {
        load,
        reload,
    } = useResourceViewer({
        fetchModel,
        resetModel,
        afterLoad: async (model) => {
            if (!model) {
                documentLoader.resetDocuments();
                return null;
            }

            const blocks = getBlocks(model.document) ?? [];

            await documentLoader.loadSentenceModelsForBlocks(blocks);
            documentLoader.hydrateBlocks(blocks);
        },
        beforeReload: () => {
            documentLoader.resetDocuments();
        },
    });

    return {
        load,
        reload,
    };
}
