import {useResourceEditor} from "../resources/useResourceEditor.js";
import {useDocumentResourceLoader} from "./useDocumentResourceLoader.js";

export function useDocumentResourceEditor({
                                              getBlocks,
                                              afterLoad = null,
                                              beforeReload = null,
                                              afterDelete = null,
                                              ...options
                                          }) {
    const documentLoader = useDocumentResourceLoader();

    const editor = useResourceEditor({
        ...options,
        afterLoad: async (model, context) => {
            await afterLoad?.(model, context);

            const blocks = getBlocks(context.form.document) ?? [];
            await documentLoader.loadSentenceModelsForBlocks(blocks);
        },
        beforeReload: async () => {
            documentLoader.resetDocuments();

            await beforeReload?.();
        },
        afterDelete: async (response) => {
            documentLoader.resetDocuments();

            await afterDelete?.(response);
        },
    });

    return {
        ...editor,
        documentLoader,
    };
}
