import {ref} from "vue";
import {useResourceLoader} from "../resources/useResourceLoader.js";

const emptyAudios = () => ({
    data: [],
    meta: {
        links: [],
        current_page: 1,
        last_page: 1,
        total: 0,
    },
});

export function useSpeakerLoader() {
    const audios = ref(emptyAudios());

    const {
        model: speaker,
        notFound: speakerNotFound,
        isLoading: isLoadingSpeaker,
        setModel: setSpeaker,
        fetchModel: fetchSpeaker,
    } = useResourceLoader({
        routeBase: 'speakers',
        routeParam: 'speaker',
        getRequestConfig: (options = {}) => ({
            params: {
                page: options.page ?? null,
            },
        }),
        extractModel: (response) => response.data.speaker ?? null,
        extractMeta: (response) => ({
            audios: response.data.audios ?? emptyAudios(),
        }),
        onSetModel: (meta = {}) => {
            audios.value = meta.audios ?? emptyAudios();
        },
    });

    return {
        speaker,
        speakerNotFound,
        isLoadingSpeaker,
        audios,
        setSpeaker,
        fetchSpeaker,
    };
}
