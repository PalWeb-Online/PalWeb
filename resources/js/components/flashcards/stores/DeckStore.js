import {defineStore} from 'pinia';
import {reactive, ref} from "vue";

export const useDeckStore = defineStore('DeckStore', () => {
    const data = reactive({
        pins: [],
        deck: {},
        cards: [],
    });

    // todo: move this to Cards page?
    const currentSlideIndex = ref(0);

    const defaultOrder = ref([]);

    const fetchDecks = async () => {
        try {
            const response = await axios.get('/dashboard/flashcards/decks');
            if (response.data && response.data.decks) {
                data.pins = response.data.decks;
            }

        } catch (error) {
            console.error('Error fetching Pinned Decks:', error);
        }
    };

    const fetchCards = async (deckId) => {
        try {
            const response = await axios.get("/dashboard/flashcards/decks/" + deckId);
            data.cards = response.data.cards;
            defaultOrder.value = [...response.data.cards];

        } catch (error) {
            console.error('Loading Failed', error);
        }
    };

    const toggleSelectDeck = (index) => {
        if (Object.keys(data.deck).length > 0 && data.deck.id === data.pins[index].id) {
            data.deck = {};

        } else {
            data.deck = data.pins[index];
        }
    }

    const reset = () => {
        data.cards = [...defaultOrder.value];
        currentSlideIndex.value = 0;
    };

    const shuffle = () => {
        data.cards = [...data.cards].sort(() => Math.random() - 0.5);
        currentSlideIndex.value = 0;
    };

    return {
        data,
        currentSlideIndex,
        fetchDecks,
        fetchCards,
        selectDeck: toggleSelectDeck,
        reset,
        shuffle,
    };
});
