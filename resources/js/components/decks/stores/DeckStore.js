import {defineStore} from 'pinia';
import {reactive, ref} from "vue";
import {cloneDeep} from "lodash";
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
        cards: [],
        stagedDeck: {
            id: '',
            name: '',
            description: '',
            terms: [],
            count: false,
            private: false,
        },
        originalDeck: null
    });

    // todo: move this to Cards page?
    const currentSlideIndex = ref(0);

    const defaultOrder = ref([]);

    const fetchCreatedDecks = async () => {
        try {
            const response = await axios.get('/workbench/deck-builder/decks');
            if (response.data && response.data.createdDecks) {
                data.user = response.data.user;
                data.decks = response.data.createdDecks;
            }

            // todo: if data.stagedDeck.id is not an id in data.decks, then the deck was deleted outside the Deck Builder while it was open; the stagedDeck should be unstaged.

        } catch (error) {
            console.error('Error fetching Created Decks:', error);
            StateStore.data.errorMessage = error;
        }
    };

    const fetchTerms = async (deckId) => {
        try {
            const response = await axios.get('/workbench/deck-builder/decks/' + deckId);
            data.stagedDeck.terms = cloneDeep(response.data.terms);
            data.originalDeck.terms = cloneDeep(response.data.terms);

        } catch (error) {
            console.error('Error fetching Deck data:', error);
            StateStore.data.errorMessage = error;
        }
    };

    const fetchPinnedDecks = async () => {
        try {
            const response = await axios.get('/workbench/card-viewer/decks');
            if (response.data && response.data.pinnedDecks) {
                data.decks = response.data.pinnedDecks;

                const stagedDeckIsPinned = data.decks.some(deck => deck.id === data.stagedDeck.id);
                if (!stagedDeckIsPinned) {
                    data.stagedDeck = {
                        id: '',
                        name: '',
                        description: '',
                        terms: [],
                        count: false,
                        private: false,
                    };
                }
            }

        } catch (error) {
            console.error('Error fetching Pinned Decks:', error);
        }
    };

    const fetchCards = async (deckId) => {
        try {
            const response = await axios.get("/workbench/card-viewer/decks/" + deckId);
            data.cards = response.data.terms;
            defaultOrder.value = [...response.data.terms];
            return true;

        } catch (error) {
            console.error('Error fetching Deck data:', error);
            return false;
        }
    };

    const toggleSelectDeck = (index) => {
        if (index !== null) {
            if (data.stagedDeck.id === data.decks[index].id) {
                data.stagedDeck = {
                    id: '',
                    name: '',
                    description: '',
                    terms: [],
                    count: false,
                    private: false,
                }
                data.originalDeck = null;

            } else {
                data.stagedDeck = data.decks[index];
                data.originalDeck = cloneDeep(data.stagedDeck);

            }

        } else {
            data.stagedDeck = {
                id: '',
                name: '',
                description: '',
                terms: [],
                count: false,
                private: false,
            }
            data.originalDeck = null;
        }
    }

    const saveDeck = async () => {
        try {
            if (data.stagedDeck.id === '') {
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
            StateStore.data.errorMessage = 'Oh no! Your Deck could not be saved.';
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
            StateStore.data.errorMessage = 'Oh no! Your Deck could not be deleted.';
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
        fetchPinnedDecks,
        fetchCreatedDecks,
        fetchCards,
        fetchTerms,
        toggleSelectDeck,
        saveDeck,
        viewDeck,
        resetDeck,
        deleteDeck,
        resetCards,
        shuffleCards,
    };
});
