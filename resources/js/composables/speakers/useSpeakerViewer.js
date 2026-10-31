import {useResourceViewer} from "../resources/useResourceViewer.js";
import {useSpeakerLoader} from "./useSpeakerLoader.js";

export function useSpeakerViewer() {
    const speakerLoader = useSpeakerLoader();

    const {
        load: loadSpeaker,
        reload: reloadSpeaker,
    } = useResourceViewer({
        fetchModel: speakerLoader.fetchShowSpeaker,
        resetModel: speakerLoader.setSpeaker,
    });

    return {
        ...speakerLoader,
        loadSpeaker,
        reloadSpeaker,
    };
}
