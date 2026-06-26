import {ref} from "vue";
import {route} from "ziggy-js";
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
        getUrl: (speakerId, options = {}) => route(options.routeName ?? 'api.speakers.fetch', {
            speaker: speakerId,
            ...(options.params ?? {}),
        }),
        getRequestConfig: (options = {}) => ({
            params: {
                include: options.include ?? null,
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
