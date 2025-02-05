import {defineStore} from 'pinia';
import {computed, reactive} from "vue";
import {useDialogStore} from "./DialogStore.js";
import {useSentenceStore} from "./SentenceStore.js";

export const useStateStore = defineStore('DialoggerStateStore', () => {
    const SentenceStore = useSentenceStore();
    const DialogStore = useDialogStore();

    const data = reactive({
        mode: 'dialog',
        step: 'dialog',
        errorMessage: null,
        isLoading: false,
    });

    const hasNavigationGuard = computed(() => {
        if (data.step === 'dialog') {
            return JSON.stringify(DialogStore.data.stagedDialog) !== JSON.stringify(DialogStore.data.originalDialog);

        } else if (data.step === 'sentence') {
            return JSON.stringify(SentenceStore.data.stagedSentence) !== JSON.stringify(SentenceStore.data.originalSentence);
        }
    });

    const isValidRequest = computed(() => {
        if (data.step === 'dialog') {
            if (!DialogStore.data.stagedDialog.title) return false;
            if (DialogStore.data.stagedDialog.sentences.length === 0) return false;

            for (const sentence of DialogStore.data.stagedDialog.sentences) {
                if (!sentence.speaker || !sentence.position) {
                    return false;
                }
            }

            return true;

        } else if (data.step === 'sentence') {
            if (!SentenceStore.data.stagedSentence.trans) return false;
            if (SentenceStore.data.stagedSentence.terms.length === 0) return false;

            for (const term of SentenceStore.data.stagedSentence.terms) {
                if (!term.sentencePivot.sent_term || !term.sentencePivot.sent_translit) {
                    return false;
                }
            }

            return true;
        }
    });

    return {
        data,
        hasNavigationGuard,
        isValidRequest,
    };
});
