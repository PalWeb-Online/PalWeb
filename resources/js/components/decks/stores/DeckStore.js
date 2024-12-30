import {defineStore} from 'pinia';
import {reactive, ref} from "vue";
import {useStateStore} from "./StateStore.js";

export const useDeckStore = defineStore('DeckStore', () => {
    const StateStore = useStateStore();

    const data = reactive({
        user: {
            name: '',
            username: '',
            avatar: '',
        },
        decks: [],
        deck: {
            id: '',
            name: '',
            description: '',
            terms: [],
            count: false,
            private: false,
        },
        terms: []
    });

    // todo: move this to Cards page?
    const currentSlideIndex = ref(0);

    const defaultOrder = ref([]);

    const fetchCreatedDecks = async () => {
        try {
            const response = await axios.get('/dashboard/workbench/deck-builder/decks');
            if (response.data && response.data.createdDecks) {
                data.user = response.data.user;
                data.decks = response.data.createdDecks;
            }

        } catch (error) {
            console.error('Error fetching Created Decks:', error);
            StateStore.data.errorMessage = error;
        }
    };

    const fetchTerms = async (deckId) => {
        try {
            const response = await axios.get('/dashboard/workbench/deck-builder/decks/' + deckId);
            data.terms = response.data.terms;

        } catch (error) {
            console.error('Error fetching Deck data:', error);
            StateStore.data.errorMessage = error;
        }
    };

    const fetchPinnedDecks = async () => {
        try {
            const response = await axios.get('/dashboard/workbench/card-viewer/decks');
            if (response.data && response.data.pinnedDecks) {
                data.decks = response.data.pinnedDecks;
            }

        } catch (error) {
            console.error('Error fetching Pinned Decks:', error);
        }
    };

    const fetchCards = async (deckId) => {
        try {
            const response = await axios.get("/dashboard/workbench/card-viewer/decks/" + deckId);
            data.terms = response.data.terms;
            defaultOrder.value = [...response.data.terms];

        } catch (error) {
            console.error('Error fetching Deck data:', error);
        }
    };

    const toggleSelectDeck = (index) => {
        if (index !== null) {
            if (data.deck.id === data.decks[index].id) {
                data.deck = {
                    id: '',
                    name: '',
                    description: '',
                    terms: [],
                    count: false,
                    private: false,
                }

            } else {
                data.deck = data.decks[index];

            }

        } else {
            data.deck = {
                id: '',
                name: '',
                description: '',
                terms: [],
                count: false,
                private: false,
            }
        }
    }

    const saveDeck = async () => {
        try {
            if (data.deck.id === '') {
                await axios.post('/community/decks', {
                    deck: data.deck,
                    terms: data.terms
                });
            } else {
                await axios.patch('/community/decks/' + data.deck.id, {
                    deck: data.deck,
                    terms: data.terms
                });
            }
            StateStore.data.errorMessage = null;
            return true;
        } catch (error) {
            StateStore.data.errorMessage = 'Oh no! Your Deck could not be saved.';
            return false;
        }
    };

    const reset = () => {
        data.terms = [...defaultOrder.value];
        currentSlideIndex.value = 0;
    };

    const shuffle = () => {
        data.terms = [...data.terms].sort(() => Math.random() - 0.5);
        currentSlideIndex.value = 0;
    };

    return {
        data,
        currentSlideIndex,
        fetchPinnedDecks,
        fetchCreatedDecks,
        fetchCards,
        fetchTerms,
        toggleSelectDeck,
        saveDeck,
        reset,
        shuffle,
    };
});
