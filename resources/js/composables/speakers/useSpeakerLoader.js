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
        fetchShowModel: fetchShowSpeaker,
    } = useResourceLoader({
        routeBase: 'speakers',
        routeParam: 'speaker',
        extractModel: (response) => response.data.speaker ?? null,
        extractMeta: (response) => ({
            audios: response.data.audios ?? emptyAudios(),
        }),
        onSetModel: (meta = {}) => {
            audios.value = meta.audios ?? emptyAudios();
        },
    });

    const fetchSpeakerAudios = async (speakerId, params = {}) => {
        const response = await axios.get(`/api/speakers/${encodeURIComponent(speakerId)}`, { params });
        audios.value = response.data.audios ?? emptyAudios();
        return audios.value;
    };

    return {
        speaker,
        speakerNotFound,
        isLoadingSpeaker,
        audios,
        setSpeaker,
        fetchShowSpeaker,
        fetchSpeakerAudios,
    };
}
