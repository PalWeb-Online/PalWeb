import {onMounted, reactive, ref, watch} from "vue";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

export function useSentence(props = {}) {
    const sentence = reactive({});
    const isLoading = ref(true);

    const {
        isDeleting: isDeletingSentence,
        deleteResource: deleteSentence,
    } = useResourceDelete({
        routeBase: 'sentences',
        label: 'Sentence',
        onDeleteSuccess: () => {
            router.get(route('sentences.index'));
        },
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

    return {
        sentence,
        isLoading,
        isDeletingSentence,
        isCurrentTerm,
        deleteSentence
    };
}
