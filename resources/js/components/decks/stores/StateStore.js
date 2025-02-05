import {defineStore} from 'pinia';
import {Inertia} from "@inertiajs/inertia";
import {computed, reactive} from "vue";
import {useDeckStore} from "./DeckStore.js";

export const useStateStore = defineStore('DeckBuilderStateStore', () => {
    const DeckStore = useDeckStore();

    const data = reactive({
        mode: 'build',
        step: 'select',
        errorMessage: '',
        isLoading: false,
    });

    const hasNavigationGuard = computed(() => {
        if (data.step === 'build') {
            return JSON.stringify(DeckStore.data.stagedDeck) !== JSON.stringify(DeckStore.data.originalDeck)

        } else {
            return false;
        }
    });

    const isValidRequest = computed(() => {
        return DeckStore.data.stagedDeck.name;
    });

    const toSelect = () => {
        Inertia.get(route('deck-master.index', {mode: data.mode}));
    }

    const toBuild = () => {
        DeckStore.data.stagedDeck?.id
            ? Inertia.get(route('deck-master.edit', DeckStore.data.stagedDeck.id))
            : Inertia.get(route('deck-master.create'))
    };

    const toStudy = () => {
        DeckStore.data.stagedDeck?.id
            && Inertia.get(route('deck-master.study', DeckStore.data.stagedDeck.id));
    };

    return {
        data,
        hasNavigationGuard,
        isValidRequest,
        toSelect,
        toBuild,
        toStudy,
    };
});
