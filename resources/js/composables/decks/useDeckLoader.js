import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useDeckLoader() {
    const {
        model: deck,
        notFound: deckNotFound,
        isLoading: isLoadingDeck,
        setModel: setDeck,
        fetchModel: fetchDeck,
    } = useResourceLoader({
        getUrl: (deckId, options = {}) => route(options.routeName ?? 'api.decks.fetch', {
            deck: deckId,
            ...(options.params ?? {}),
        }),
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
        fetchDeck,
    };
}
