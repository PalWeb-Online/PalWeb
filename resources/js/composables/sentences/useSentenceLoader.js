import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useSentenceLoader() {
    const {
        model: sentence,
        notFound: sentenceNotFound,
        isLoading: isLoadingSentence,
        setModel: setSentence,
        fetchShowModel: fetchShowSentence,
        fetchEditModel: fetchEditSentence,
    } = useResourceLoader({
        routeBase: 'sentences',
        routeParam: 'sentence',
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
        fetchShowSentence,
        fetchEditSentence,
    };
}
