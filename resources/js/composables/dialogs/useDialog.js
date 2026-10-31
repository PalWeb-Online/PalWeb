import {onMounted, reactive, ref} from "vue";
import {useResourceDelete} from "../resources/useResourceDelete.js";
import {useRouter} from "vue-router";

export function useDialog(props = {}) {
    const router = useRouter();
    const dialog = reactive({});
    const isLoading = ref(true);
    const isLoadingTerms = ref(true);

    const {
        isDeleting: isDeletingDialog,
        deleteResource: deleteDialog,
    } = useResourceDelete({
        label: 'dialog',
        routeBase: 'dialogs',
        onDeleteSuccess: () => {
            router.push({name: 'dialogs.index'});
        },
    });

    const initDialog = (model) => {
        if (!model) return;
        Object.assign(dialog, model);

        fetchSentenceTerms();
    }

    onMounted(() => {
        if (props?.model) {
            initDialog(props.model);
        } else {
            isLoadingTerms.value = false;
        }

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
            const { data } = await axios.post('/api/sentences/many', {
                ids: Array.from(ids)
            });

            dialog.sentences.forEach(sentence => {
                sentence.terms = data.sentences[sentence.id].terms
            })

        } catch (error) {
            console.error("Failed to fetch Dialog Sentences", error);

        } finally {
            isLoadingTerms.value = false;
        }
    }

    return {
        dialog,
        isLoading,
        isLoadingTerms,
        isDeletingDialog,
        deleteDialog
    };
}
