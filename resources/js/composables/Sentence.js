import {onMounted, reactive, ref, watch} from "vue";
import {Howl} from "howler";

export function useSentence(props) {
    const sentence = reactive({});
    const audio = ref(null);
    const isLoading = ref(true);
    const isPlaying = ref(false);

    const playAudio = () => {
        if (audio.value) {
            audio.value.play();
        }
    }

    const createHowlInstance = (newSentence) => {
        if (audio.value) {
            audio.value.unload();
        }

        if (newSentence.audio) {
            audio.value = new Howl({
                src: [`https://abdulbaha.fra1.digitaloceanspaces.com/audios/${newSentence.audio}`],
                onplay: () => isPlaying.value = true,
                onend: () => isPlaying.value = false,
                onstop: () => isPlaying.value = false,
                onpause: () => isPlaying.value = false,
            });

        } else {
            audio.value = null;
        }
    }

    const isCurrentTerm = (term) => {
        return term.id === props.currentTerm;
    }

    onMounted(() => {
        Object.assign(sentence, props.model);
        createHowlInstance(sentence);
        isLoading.value = false;
    });

    watch(() => props.model,
        (newSentence) => {
            Object.assign(sentence, newSentence);
            createHowlInstance(sentence);
        },
        {deep: true}
    );

    return {sentence, isLoading, isPlaying, isCurrentTerm, playAudio};
}
