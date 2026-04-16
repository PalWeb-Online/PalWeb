import {onMounted, reactive, ref, watch} from "vue";
import {useAudio} from "./Audio.js";

export function useSentence(props) {
    const sentence = reactive({});
    const isLoading = ref(true);

    const {isPlaying, createAudio, playAudio} = useAudio();

    const isCurrentTerm = (term) => {
        return term.id === props.currentTerm;
    };

    onMounted(() => {
        Object.assign(sentence, props.model);
        createAudio(sentence.audio);
        isLoading.value = false;
    });

    watch(() => props.model,
        (newSentence) => {
            Object.assign(sentence, newSentence);
            createAudio(sentence.audio);
        },
        {deep: true}
    );

    return {sentence, isLoading, isPlaying, isCurrentTerm, playAudio};
}
