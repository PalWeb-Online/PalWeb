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

    const toggleSelectDeck = (index) => {
        if (index !== null) {
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
        initializeDeck,
        toggleSelectDeck,
        saveDeck,
        resetDeck,
        resetCards,
        shuffleCards,
    };
});
