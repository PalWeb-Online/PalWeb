import {onMounted, reactive, ref} from "vue";
import {route} from "ziggy-js";

export function useDialog(props) {
    const dialog = reactive({});
    const isLoading = ref(true);
    const isLoadingTerms = ref(true);

    const initDialog = (model) => {
        if (!model) return;
        Object.assign(dialog, props.model);

        fetchSentenceTerms();
    }

    onMounted(() => {
        if (props?.model) initDialog(props.model);
        isLoading.value = false;
    });

    const fetchSentenceTerms = async () => {
        if (!dialog || !dialog.sentences) {
            isLoadingTerms.value = false;
            return;
        }

        const ids = new Set();
        dialog.sentences.forEach(sentence => {
            ids.add(sentence.id);
        });

        if (ids.size === 0) {
            isLoadingTerms.value = false;
            return;
        }

        try {
            const response = await axios.post(route('sentences.get-many'), {
                ids: Array.from(ids)
            });

            dialog.sentences.forEach(sentence => {
                sentence.terms = response.data[sentence.id].terms
            })

        } catch (error) {
            console.error("Failed to fetch Dialog Sentences", error);

        } finally {
            isLoadingTerms.value = false;
        }
    }

    return {dialog, isLoading, isLoadingTerms};
}
