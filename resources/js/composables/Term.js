import {onMounted, reactive, ref, watch} from "vue";
import {Howl} from "howler";

export function useTerm(props) {
    const term = reactive({});
    const audio = ref(null);
    const isLoading = ref(true);
    const isPlaying = ref(false);

    const playAudio = () => {
        if (audio.value) {
            audio.value.play();
        }
    }

    const createHowlInstance = (newTerm) => {
        if (audio.value) {
            audio.value.unload();
        }

        if (newTerm.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${newTerm.audio}`],
                onplay: () => isPlaying.value = true,
                onend: () => isPlaying.value = false,
                onstop: () => isPlaying.value = false,
                onpause: () => isPlaying.value = false,
            });
        } else {
            audio.value = null;
        }
    };

    onMounted(() => {
        Object.assign(term, props.model);
        createHowlInstance(term);
        isLoading.value = false;
    });

    watch(() => props.model,
        (newTerm) => {
            Object.assign(term, newTerm);
            createHowlInstance(term);
        },
        {deep: true}
    );

    return {term, isLoading, isPlaying, playAudio};
}
