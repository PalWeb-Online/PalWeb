import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useDeckLoader} from "./useDeckLoader.js";

export function useDeckViewer() {
    const deckLoader = useDeckLoader();

    const {
        load: loadDeck,
        loadRandom: loadRandomDeck,
        reload: reloadDeck,
        reloadRandom: reloadRandomDeck,
    } = useResourceViewer({
        fetchModel: deckLoader.fetchShowDeck,
        fetchRandomModel: deckLoader.fetchRandomDeck,
        resetModel: deckLoader.setDeck,
    });

    return {
        ...deckLoader,
        loadDeck,
        loadRandomDeck,
        reloadDeck,
        reloadRandomDeck,
    };
}
