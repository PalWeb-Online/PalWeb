import {useDocumentLoader} from "../useDocumentLoader.js";

export function useDocumentResourceViewer({
                                              fetchModel,
                                              resetModel,
                                              getBlocks,
                                          }) {
    const documentLoader = useDocumentLoader();

    const load = async (...args) => {
        const model = await fetchModel(...args);

        if (!model) {
            documentLoader.resetDocuments();
            return null;
        }

        const blocks = getBlocks(model.document) ?? [];

        await documentLoader.loadSentenceModelsForBlocks(blocks);
        documentLoader.hydrateBlocks(blocks);

        return model;
    };

    const reload = async (...args) => {
        resetModel();
        documentLoader.resetDocuments();

        return await load(...args);
    };

    return {
        load,
        reload,
    };
}
