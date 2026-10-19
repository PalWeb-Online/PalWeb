import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useDeckLoader} from "./useDeckLoader.js";

export function useDeckViewer() {
    const deckLoader = useDeckLoader();

    const {
        load: loadDeck,
        reload: reloadDeck,
    } = useResourceViewer({
        fetchModel: deckLoader.fetchShowDeck,
        resetModel: deckLoader.setDeck,
    });

    return {
        ...deckLoader,
        loadDeck,
        reloadDeck,
    };
}
