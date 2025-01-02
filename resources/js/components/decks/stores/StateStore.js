import {defineStore} from 'pinia';
import {computed, reactive} from "vue";
import {useDeckStore} from "./DeckStore.js";

export const useStateStore = defineStore('StateStore', () => {
    const DeckStore = useDeckStore();

    const data = reactive({
        context: null,
        action: 'create',
        step: 'select',
        errorMessage: '',
    });

    const steps = computed(() => {
        const isBuilder = data.context === 'builder';
        return {
            select: {
                backStep: null,
                nextStep: isBuilder ? 'build' : 'study',
                canMoveBack: () => false,
                canMoveNext: () => isBuilder || DeckStore.data.stagedDeck.id,
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
        return JSON.stringify(DeckStore.data.stagedDeck) !== JSON.stringify(DeckStore.data.originalDeck);
    });

    const backDisabled = computed(() => !steps.value[data.step]?.canMoveBack());
    const nextDisabled = computed(() => !steps.value[data.step]?.canMoveNext());

    const initialize = (action = 'create') => {
        data.action = action;
        if (action === 'edit') {
            data.step = 'build';
        }
    };

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

    const exit = async () => {
        if (hasUnsavedChanges.value && !confirm('Are you sure you would like to exit? All your unsaved changes will be lost.')) return;
        window.location.href = '/dashboard/workbench';
    };

    return {
        data,
        hasUnsavedChanges,
        backDisabled,
        nextDisabled,
        initialize,
        back,
        next,
        exit,
    };
});
