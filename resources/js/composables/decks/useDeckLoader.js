import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useDeckLoader() {
    const {
        model: deck,
        notFound: deckNotFound,
        isLoading: isLoadingDeck,
        setModel: setDeck,
        fetchShowModel: fetchShowDeck,
        fetchEditModel: fetchEditDeck,
    } = useResourceLoader({
        routeBase: 'decks',
        routeParam: 'deck',
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
            },
        }),
        extractModel: (response) => response.data.deck ?? null,
    });

    return {
        deck,
        deckNotFound,
        isLoadingDeck,
        setDeck,
        fetchShowDeck,
        fetchEditDeck,
    };
}
