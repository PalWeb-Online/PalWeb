import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useSentenceLoader} from "./useSentenceLoader.js";

export function useSentenceViewer() {
    const sentenceLoader = useSentenceLoader();

    const {
        load: loadSentence,
        loadRandom: loadRandomSentence,
        reload: reloadSentence,
        reloadRandom: reloadRandomSentence,
    } = useResourceViewer({
        fetchModel: sentenceLoader.fetchShowSentence,
        fetchRandomModel: sentenceLoader.fetchRandomSentence,
        resetModel: sentenceLoader.setSentence,
    });

    return {
        ...sentenceLoader,
        loadSentence,
        loadRandomSentence,
        reloadSentence,
        reloadRandomSentence,
    };
}
