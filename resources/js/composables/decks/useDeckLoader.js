import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useDeckLoader() {
    const {
        model: deck,
        notFound: deckNotFound,
        isLoading: isLoadingDeck,
        setModel: setDeck,
        fetchShowModel: fetchShowDeck,
        fetchEditModel: fetchEditDeck,
        fetchRandomModel: fetchRandomDeck,
    } = useResourceLoader({
        routeBase: 'decks',
        routeParam: 'deck',
        extractModel: (response) => response.data.deck ?? null,
    });

    return {
        deck,
        deckNotFound,
        isLoadingDeck,
        setDeck,
        fetchShowDeck,
        fetchEditDeck,
        fetchRandomDeck,
    };
}
