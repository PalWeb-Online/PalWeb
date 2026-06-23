import {onMounted, reactive, ref, watch} from "vue";
import {useResourceActions} from "../resources/useResourceActions.js";

export function useSentence(props = {}) {
    const sentence = reactive({});
    const isLoading = ref(true);

    const {
        deleteResource: deleteSentence
    } = useResourceActions({
        routeBase: 'sentences',
        label: 'Sentence',
    });

    const isCurrentTerm = (term) => {
        return term.id === props.currentTerm;
    };

    onMounted(() => {
        if (!props?.model) {
            isLoading.value = false;
            return;
        }

        Object.assign(sentence, props.model);
        isLoading.value = false;
    });

    watch(() => props?.model,
        (newSentence) => {
            if (!newSentence) return;

            Object.assign(sentence, newSentence);
        },
        {deep: true}
    );

    return {sentence, isLoading, isCurrentTerm, deleteSentence};
}
