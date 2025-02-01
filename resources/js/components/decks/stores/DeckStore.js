import {defineStore} from 'pinia';
import {reactive, ref} from "vue";
import {cloneDeep} from "lodash";
import {useStateStore} from "./StateStore.js";
import {useUserStore} from "../../../stores/UserStore.js";

export const useDeckStore = defineStore('DeckStore', () => {
    const UserStore = useUserStore();
    const StateStore = useStateStore();

    const data = reactive({
        decks: [],
        cards: [],
        stagedDeck: {},
        originalDeck: null
    });

    // todo: move this to Cards page?
    const currentSlideIndex = ref(0);

    const defaultOrder = ref([]);

    const setDecks = async () => {
        StateStore.data.isLoading = true;

        data.decks = [];

        if (StateStore.data.mode === 'build') {
            data.decks = UserStore.user.decks;

        } else if (StateStore.data.mode === 'study') {
            await fetchPinnedDecks();
        }

        StateStore.data.isLoading = false;
    }

    const initializeDeck = () => ({
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
    });

    const fetchPinnedDecks = async () => {
        try {
            const response = await axios.get('/workbench/deck-master/study/decks');
            if (response.data && response.data.pinnedDecks) {
                data.decks = response.data.pinnedDecks;

                const stagedDeckIsPinned = data.decks.some(deck => deck.id === data.stagedDeck.id);
                if (!stagedDeckIsPinned) {
                    data.stagedDeck = initializeDeck();
                }
            }

        } catch (error) {
            console.error('Error fetching Pinned Decks:', error);
        }
    };

    const toggleSelectDeck = (index) => {
        if (index) {
            if (data.stagedDeck.id === data.decks[index].id) {
                data.stagedDeck = initializeDeck();
                data.originalDeck = null;

            } else {
                data.stagedDeck = data.decks[index];
                data.originalDeck = cloneDeep(data.stagedDeck);

            }

        } else {
            data.stagedDeck = initializeDeck();
            data.originalDeck = null;
        }
    }

    const saveDeck = async () => {
        try {
            if (!data.stagedDeck.id) {
                const response = await axios.post('/community/decks', {
                    deck: data.stagedDeck,
                });

                data.stagedDeck.id = response.data.deck.id;

            } else {
                await axios.patch('/community/decks/' + data.stagedDeck.id, {
                    deck: data.stagedDeck,
                });
            }

            data.originalDeck = cloneDeep(data.stagedDeck);

            StateStore.data.errorMessage = null;
            return true;

        } catch (error) {
            StateStore.data.errorMessage = error.response?.data?.message || 'Oh no! Your Deck could not be saved.';
            return false;
        }
    };

    const resetDeck = async () => {
        data.stagedDeck = cloneDeep(data.originalDeck);
    };

    const viewDeck = async () => {
        window.open(`/community/decks/${data.stagedDeck.id}`, '_blank');
    };

    const deleteDeck = async () => {
        try {
            await axios.delete('/community/decks/' + data.stagedDeck.id);

            StateStore.data.errorMessage = null;
            return true;

        } catch (error) {
            StateStore.data.errorMessage = error.response?.data?.message || 'Oh no! Your Deck could not be deleted.';
            return false;
        }
    };

    const resetCards = () => {
        data.cards = [...defaultOrder.value];
        currentSlideIndex.value = 0;
    };

    const shuffleCards = () => {
        data.cards = [...data.cards].sort(() => Math.random() - 0.5);
        currentSlideIndex.value = 0;
    };

    return {
        data,
        currentSlideIndex,
        setDecks,
        initializeDeck,
        fetchPinnedDecks,
        toggleSelectDeck,
        saveDeck,
        viewDeck,
        resetDeck,
        deleteDeck,
        resetCards,
        shuffleCards,
    };
});
