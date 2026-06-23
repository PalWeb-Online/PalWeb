import {onMounted, onUnmounted, ref, toValue, watch} from "vue";
import {Howl} from "howler";
import {useResourceActions} from "../resources/useResourceActions.js";

export function useAudio(url = null) {
    const audio = ref(null);
    const isPlaying = ref(false);

    const {
        deleteResource: deleteAudio
    } = useResourceActions({
        routeBase: 'audios',
        label: 'Audio',
    });

    const unmountAudio = () => {
        if (audio.value) {
            audio.value.unload();
            audio.value = null;
        }

        isPlaying.value = false;
    };

    const mountAudio = (url) => {
        unmountAudio();

        if (!url) {
            return;
        }

        audio.value = new Howl({
            src: [url],
            onplay: () => isPlaying.value = true,
            onend: () => isPlaying.value = false,
            onstop: () => isPlaying.value = false,
            onpause: () => isPlaying.value = false,
        });
    };

    const playAudio = () => {
        audio.value?.play();
    };

    onMounted(() => {
        mountAudio(toValue(url));
    });

    watch(() => toValue(url),
        (newUrl) => {
            console.log('newUrl', newUrl);
            mountAudio(newUrl);
        },
        {deep: true}
    );

    onUnmounted(() => {
        unmountAudio();
    });

    return {
        audio,
        isPlaying,
        playAudio,
        deleteAudio,
    };
}
