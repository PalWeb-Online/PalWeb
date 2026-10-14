import {onMounted, reactive, ref, watch} from "vue";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useRouter} from "vue-router";

export function useSentence(props = {}) {
    const router = useRouter();
    const sentence = reactive({});
    const isLoading = ref(true);

    const {
        isDeleting: isDeletingSentence,
        deleteResource: deleteSentence,
    } = useResourceDelete({
        label: 'sentence',
        routeBase: 'sentences',
        onDeleteSuccess: () => {
            router.push({name: 'sentences.index'});
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
