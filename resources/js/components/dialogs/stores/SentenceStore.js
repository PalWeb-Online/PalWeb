import {defineStore} from 'pinia';
import {reactive} from "vue";
import {cloneDeep} from "lodash";
import {useStateStore} from "./StateStore.js";
import {useDialogStore} from "./DialogStore.js";

export const useSentenceStore = defineStore('SentenceStore', () => {
    const StateStore = useStateStore();
    const DialogStore = useDialogStore();

    const data = reactive({
        stagedSentence: {
            id: null,
            sentence: '',
            translit: '',
            trans: '',
            terms: [],
        },
        originalSentence: null,
    });

    const toggleSelectSentence = (index) => {
        data.stagedSentence = DialogStore.data.stagedDialog.sentences[index];
    }

    const saveSentence = async () => {
        try {
            if (!data.stagedSentence.id) {
                const response = await axios.post('/dictionary/sentences', {
                    sentence: data.stagedSentence,
                });

                data.stagedSentence.id = response.data.sentence.id;
                data.stagedSentence.sentence = response.data.sentence.sentence;
                data.stagedSentence.translit = response.data.sentence.translit;

            } else {
                await axios.patch('/dictionary/sentences/' + data.stagedSentence.id, {
                    sentence: data.stagedSentence,
                });
            }

            data.originalSentence = cloneDeep(data.stagedSentence);

            StateStore.data.errorMessage = null;
            return true;

        } catch (error) {
            StateStore.data.errorMessage = error.response?.data?.message || 'Oh no! The Sentence could not be saved.';
            return false;
        }
    };

    const resetSentence = async () => {
        data.stagedSentence = cloneDeep(data.originalSentence);
    };

    const viewSentence = async () => {
        window.open(`/dictionary/sentences/${data.stagedSentence.id}`, '_blank');
    };

    const deleteSentence = async () => {
        try {
            await axios.delete(`/dictionary/sentences/${data.stagedSentence.id}`);

            StateStore.data.errorMessage = null;

            if (StateStore.data.modelType === 'sentence') {
                setTimeout(() => {
                    window.location.href = '/dictionary/sentences';
                }, 1000);

            } else {
                return true;
            }

        } catch (error) {
            StateStore.data.errorMessage = error.response?.data?.message || 'Oh no! The Sentence could not be deleted.';
            return false;
        }
    };

    return {
        data,
        saveSentence,
        resetSentence,
        viewSentence,
        deleteSentence,
        toggleSelectSentence,
    }
});
