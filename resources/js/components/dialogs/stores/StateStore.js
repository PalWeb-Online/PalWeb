import {defineStore} from 'pinia';
import {computed, reactive} from "vue";
import {useDialogStore} from "./DialogStore.js";
import {useSentenceStore} from "./SentenceStore.js";

export const useStateStore = defineStore('StateStore', () => {
    const SentenceStore = useSentenceStore();
    const DialogStore = useDialogStore();

    const data = reactive({
        modelType: 'dialog',
        step: 'dialog',
        errorMessage: null,
    });

    const steps = computed(() => {
        return {
            dialog: {
                backStep: null,
                nextStep: 'sentence',
                canMoveBack: () => false,
                canMoveNext: () => SentenceStore.data.stagedSentence.id,
            },
            sentence: {
                backStep: 'dialog',
                nextStep: null,
                canMoveBack: () => data.modelType === 'dialog',
                canMoveNext: () => false,
            }
        }
    });

    const hasUnsavedChanges = computed(() => {
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

    const backDisabled = computed(() => !steps.value[data.step]?.canMoveBack());
    const nextDisabled = computed(() => !steps.value[data.step]?.canMoveNext());

    const back = async () => {
        const currentStep = steps.value[data.step];

        if (currentStep?.canMoveBack()) {
            if ((hasUnsavedChanges.value || !SentenceStore.data.stagedSentence.id) && !confirm('Are you sure you would like to return to the Select page? All your unsaved changes will be lost.')) return;
            Object.assign(SentenceStore.data.stagedSentence, SentenceStore.data.originalSentence);
            data.step = currentStep.backStep;
        }
    };

    const next = async () => {
        const currentStep = steps.value[data.step];

        if (currentStep?.canMoveNext()) {
            data.step = currentStep.nextStep;
        }
    };

    const exit = async () => {
        if (hasUnsavedChanges.value && !confirm('Are you sure you would like to exit? All your unsaved changes will be lost.')) return;
        window.location.href = '/academy/dialogs';
    };

    return {
        data,
        hasUnsavedChanges,
        isValidRequest,
        backDisabled,
        nextDisabled,
        back,
        next,
        exit,
    };
});
