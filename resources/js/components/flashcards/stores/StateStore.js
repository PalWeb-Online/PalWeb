import {defineStore} from 'pinia';
import {computed, reactive} from "vue";
import {useDeckStore} from "./DeckStore.js";

export const useStateStore = defineStore('StateStore', () => {
    const DeckStore = useDeckStore();

    const data = reactive({
        step: 'decks',
    });

    const steps = {
        decks: {
            backStep: null,
            nextStep: 'cards',
            canMoveBack: () => false,
            canMoveNext: () => Object.keys(DeckStore.data.deck).length > 0,
        },
        cards: {
            backStep: 'decks',
            nextStep: null,
            canMoveBack: () => true,
            canMoveNext: () => false,
        },
    };

    const backDisabled = computed(() => !steps[data.step]?.canMoveBack());
    const nextDisabled = computed(() => !steps[data.step]?.canMoveNext());

    const back = async () => {
        const currentStep = steps[data.step];
        if (currentStep?.canMoveBack()) {
            data.step = currentStep.backStep;
        }
    };

    const next = async () => {
        const currentStep = steps[data.step];
        if (currentStep?.canMoveNext()) {
            data.step = currentStep.nextStep;
        }
    };

    const exit = async () => {
        window.location.href = '/dashboard/workbench';
    };

    return {
        data,
        backDisabled,
        nextDisabled,
        back,
        next,
        exit
    };
});
