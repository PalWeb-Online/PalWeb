import {defineStore} from 'pinia';
import {Inertia} from "@inertiajs/inertia";
import {reactive} from "vue";
import {useUserStore} from "../../../../stores/UserStore.js";

export const useDeckMasterStore = defineStore('DeckMasterStore', () => {
    const UserStore = useUserStore();

    const data = reactive({
        mode: 'build',
        step: 'select',
        errorMessage: '',
        isLoading: true,
        decks: [],
        stagedDeck: {
            id: null,
            name: '',
            description: '',
            private: false,
            isPinned: false,
            pinCount: 0,
            created_at: null,
            author: {
                id: UserStore.user.id,
                name: UserStore.user.name,
                username: UserStore.user.username,
                avatar: UserStore.user.avatar,
            },
            terms: [],
        },
        originalDeck: null,
        deckCards: [],
        activeId: null,
    });

    const initializeDeck = () => {
        data.stagedDeck = {
            id: null,
            name: '',
            description: '',
            private: false,
            isPinned: false,
            pinCount: 0,
            created_at: null,
            author: {
                id: UserStore.user.id,
                name: UserStore.user.name,
                username: UserStore.user.username,
                avatar: UserStore.user.avatar,
            },
            terms: [],
        };

        data.originalDeck = null;
    };

    const fetchDecks = async (mode) => {
        try {
            data.isLoading = true;
            const response = await axios.get(route('deck-master.get-decks'), {
                params: {mode}
            });

            data.decks = response.data.decks;
            data.isLoading = false;

        } catch (error) {
            console.error("Failed to fetch decks", error);
        }
    };

    const toSelect = () => {
        Inertia.get(route('deck-master.index', {mode: data.mode}));
    }

    const toBuild = () => {
        data.stagedDeck?.id
            ? Inertia.get(route('deck-master.edit', data.stagedDeck.id))
            : Inertia.get(route('deck-master.create'))
    };

    const toStudy = () => {
        data.stagedDeck?.id
        && Inertia.get(route('deck-master.study', data.stagedDeck.id));
    };

    return {
        data,
        initializeDeck,
        fetchDecks,
        toSelect,
        toBuild,
        toStudy,
    };
});
