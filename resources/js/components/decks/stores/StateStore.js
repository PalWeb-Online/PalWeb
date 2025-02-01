import {defineStore} from 'pinia';
import {computed, onBeforeUnmount, onMounted, reactive} from "vue";
import {useDeckStore} from "./DeckStore.js";

export const useStateStore = defineStore('StateStore', () => {
    const DeckStore = useDeckStore();

    const data = reactive({
        mode: 'build',
        step: 'select',
        errorMessage: '',
        isLoading: false,
    });

    const steps = computed(() => {
        return {
            select: {
                backStep: null,
                nextStep: data.mode === 'build' ? 'build' : 'study',
                canMoveBack: () => false,
                canMoveNext: () => data.mode === 'build' || DeckStore.data.stagedDeck.id,
            },
            build: {
                backStep: 'select',
                nextStep: null,
                canMoveBack: () => true,
                canMoveNext: () => false,
            },
            study: {
                backStep: 'select',
                nextStep: null,
                canMoveBack: () => true,
                canMoveNext: () => false,
            }
        }
    });

    const hasUnsavedChanges = computed(() => {
        if (data.step === 'build') {
            return JSON.stringify(DeckStore.data.stagedDeck) !== JSON.stringify(DeckStore.data.originalDeck)

        } else {
            return false;
        }
    });

    const backDisabled = computed(() => !steps.value[data.step]?.canMoveBack());
    const nextDisabled = computed(() => !steps.value[data.step]?.canMoveNext());

    const back = async () => {
        const currentStep = steps.value[data.step];

        if (currentStep?.canMoveBack()) {
            if (hasUnsavedChanges.value && !confirm('Are you sure you would like to return to the Select page? All your unsaved changes will be lost.')) return;
            data.step = currentStep.backStep;
        }
    };

    const next = async () => {
        const currentStep = steps.value[data.step];

        if (currentStep?.canMoveNext()) {
            data.step = currentStep.nextStep;
        }
    };

    const handleUnsavedChanges = (event) => {
        if (hasUnsavedChanges.value) {
            event.preventDefault();
            event.returnValue = '';
        }
    };

    onMounted(() => {
        window.addEventListener('beforeunload', handleUnsavedChanges);
    });

    onBeforeUnmount(() => {
        window.removeEventListener('beforeunload', handleUnsavedChanges);
    });

    return {
        data,
        hasUnsavedChanges,
        backDisabled,
        nextDisabled,
        back,
        next,
    };
});
