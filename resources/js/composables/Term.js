import {onMounted, reactive, ref, watch} from "vue";
import {useAudio} from "./Audio.js";

export function useTerm(props) {
    const term = reactive({});
    const isLoading = ref(true);

    const {isPlaying, createAudio, playAudio} = useAudio();

    onMounted(() => {
        Object.assign(term, props.model);
        createAudio(term.audio);
        isLoading.value = false;
    });

    watch(() => props.model,
        (newTerm) => {
            Object.assign(term, newTerm);
            createAudio(term.audio);
        },
        {deep: true}
    );

    return {term, isLoading, isPlaying, playAudio};
}
