import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useSentenceLoader} from "./useSentenceLoader.js";

export function useSentenceViewer() {
    const sentenceLoader = useSentenceLoader();

    const {
        load: loadSentence,
        reload: reloadSentence,
    } = useResourceViewer({
        fetchModel: (sentenceId, options = {}) => sentenceLoader.fetchSentence(sentenceId, {
            include: 'show',
            ...options,
        }),
        resetModel: sentenceLoader.setSentence,
    });

    return {
        ...sentenceLoader,
        loadSentence,
        reloadSentence,
    };
}
