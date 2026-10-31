import {onMounted, onUnmounted, ref, toValue, watch} from "vue";
import {Howl} from "howler";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useRouter} from "vue-router";

export function useAudio(url = null) {
    const router = useRouter();
    const audio = ref(null);
    const isPlaying = ref(false);

    const {
        isDeleting: isDeletingAudio,
        deleteResource: deleteAudio,
    } = useResourceDelete({
        label: 'audio',
        routeBase: 'audios',
        onDeleteSuccess: () => {
            router.push({name: 'audios.index'});
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
