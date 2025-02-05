import {defineStore} from 'pinia';
import {reactive} from "vue";
import {route} from "ziggy-js";
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

    const selectSentence = (index) => {
        data.stagedSentence = reactive(DialogStore.data.stagedDialog.sentences[index]);
        StateStore.data.step = 'sentence';
    }

    const saveSentence = async () => {
        try {
            let response;

            if (!data.stagedSentence.id) {
                response = await axios.post('/dictionary/sentences', {
                    sentence: data.stagedSentence,
                });

                data.stagedSentence.id = response.data.sentence.id;
                data.stagedSentence.dialog_id = response.data.sentence.dialog_id;

            } else {
                response = await axios.patch('/dictionary/sentences/' + data.stagedSentence.id, {
                    sentence: data.stagedSentence,
                });
            }

            data.stagedSentence.sentence = response.data.sentence.sentence;
            data.stagedSentence.translit = response.data.sentence.translit;

            data.originalSentence = cloneDeep(data.stagedSentence);


            if (StateStore.data.modelType === 'dialog') {
                const index = DialogStore.data.originalDialog.sentences.findIndex(
                    sentence => sentence.id === data.stagedSentence.id
                );

                if (index !== -1) {
                    Object.assign(DialogStore.data.originalDialog.sentences[index], {
                        sentence: data.stagedSentence.sentence,
                        translit: data.stagedSentence.translit,
                        trans: data.stagedSentence.trans,
                        terms: data.stagedSentence.terms,
                    });
                }
            }


            StateStore.data.errorMessage = null;
            return true;

        } catch (error) {
            console.log('error');
            StateStore.data.errorMessage = error.response?.data?.message || 'Oh no! The Sentence could not be saved.';
            return false;
        }
    };

    const resetSentence = async () => {
        data.stagedSentence = cloneDeep(data.originalSentence);
    };

    return {
        data,
        selectSentence,
        saveSentence,
        resetSentence,
    }
});
