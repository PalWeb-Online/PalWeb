import {route} from "ziggy-js";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useSentenceLoader() {
    const {
        model: sentence,
        notFound: sentenceNotFound,
        isLoading: isLoadingSentence,
        setModel: setSentence,
        fetchModel: fetchSentence,
    } = useResourceLoader({
        getUrl: (sentenceId, options = {}) => route(options.routeName ?? 'api.sentences.fetch', {
            sentence: sentenceId,
            ...(options.params ?? {}),
        }),
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
            },
        }),
        extractModel: (response) => response.data.sentence ?? null,
    });

    return {
        sentence,
        sentenceNotFound,
        isLoadingSentence,
        setSentence,
        fetchSentence,
    };
}
