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

    const stripBlankExerciseTips = (blocks = []) => {
        if (!Array.isArray(blocks)) {
            return;
        }

        blocks.forEach((block) => {
            if (block?.type === 'container') {
                stripBlankExerciseTips(block.blocks);
            }

            if (block?.type !== 'exercises' || !Array.isArray(block.items)) {
                return;
            }

            block.items.forEach((exercise) => {
                if (
                    exercise
                    && Object.prototype.hasOwnProperty.call(exercise, 'tip')
                    && (typeof exercise.tip !== 'string' || exercise.tip.trim() === '')
                ) {
                    delete exercise.tip;
                }
            });
        });
    };

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
        beforeSave: (saveOptions, context) => {
            const blocks = getBlocks(context.form.document) ?? [];
            stripBlankExerciseTips(blocks);

            return options.beforeSave?.(saveOptions, context);
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
