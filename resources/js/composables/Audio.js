import {onUnmounted, ref} from "vue";
import {Howl} from "howler";

export function useAudio() {
    const audio = ref(null);
    const isPlaying = ref(false);

    const destroyAudio = () => {
        if (audio.value) {
            audio.value.unload();
            audio.value = null;
        }

        isPlaying.value = false;
    };

    const createAudio = (url) => {
        destroyAudio();

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
        if (audio.value) {
            audio.value.play();
        }
    };

    onUnmounted(() => {
        destroyAudio();
    });

    return {
        audio,
        isPlaying,
        createAudio,
        destroyAudio,
        playAudio,
    };
}
