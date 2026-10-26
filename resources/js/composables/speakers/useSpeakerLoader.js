import {ref} from "vue";
import {useResourceLoader} from "../resources/useResourceLoader.js";

export function useSpeakerLoader() {
    const audios = ref([]);
    const audioPagination = ref(null);

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
            audios: response.data.audios.data ?? [],
            audioPagination: response.data.audios.meta ?? null,
        }),
        onSetModel: (meta = {}) => {
            audios.value = meta.audios ?? [];
            audioPagination.value = meta.audioPagination ?? null;
        },
    });

    return {
        speaker,
        speakerNotFound,
        isLoadingSpeaker,
        audios,
        audioPagination,
        setSpeaker,
        fetchSpeaker,
    };
}
