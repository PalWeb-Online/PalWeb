import {onMounted, onUnmounted, ref, toValue, watch} from "vue";
import {Howl} from "howler";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

export function useAudio(url = null) {
    const audio = ref(null);
    const isPlaying = ref(false);

    const {
        isDeleting: isDeletingAudio,
        deleteResource: deleteAudio,
    } = useResourceDelete({
        routeBase: 'audios',
        label: 'Audio',
        onDeleteSuccess: () => {
            router.get(route('audios.index'));
        },
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
        isDeletingAudio,
        playAudio,
        deleteAudio,
    };
}
