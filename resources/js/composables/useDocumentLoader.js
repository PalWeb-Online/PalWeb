import axios from "axios";
import {ref} from "vue";
import {route} from "ziggy-js";

export function useDocumentLoader() {
    const sentenceModels = ref({});

    const collectSentenceIds = (blocks, ids = new Set()) => {
        if (!Array.isArray(blocks)) {
            return ids;
        }

        for (const block of blocks) {
            if (!block || typeof block !== 'object') {
                continue;
            }

            if (block.type === 'sentence' && block.model?.id) {
                ids.add(block.model.id);
            }

            if (block.type === 'container' && Array.isArray(block.blocks)) {
                collectSentenceIds(block.blocks, ids);
            }
        }

        return ids;
    };

    const loadSentenceModelsForBlocks = async (blocks) => {
        const ids = Array.from(collectSentenceIds(blocks));

        if (ids.length === 0) {
            sentenceModels.value = {};
            return;
        }

        const response = await axios.post(route('sentences.get-many'), {
            ids,
        });

        sentenceModels.value = response.data ?? {};
    };

    const resetDocuments = () => {
        sentenceModels.value = {};
    };

    const hydrateBlocks = (blocks) => {
        if (!Array.isArray(blocks)) {
            return;
        }

        for (const block of blocks) {
            if (!block || typeof block !== 'object') {
                continue;
            }

            if (block.type === 'sentence') {
                const modelId = block.model?.id;

                if (modelId) {
                    block.model = sentenceModels.value[modelId];
                }
            }

            if (block.type === 'container' && Array.isArray(block.blocks)) {
                hydrateBlocks(block.blocks);
            }
        }
    };

    return {
        sentenceModels,
        hydrateBlocks,
        loadSentenceModelsForBlocks,
        resetDocuments,
    };
}
