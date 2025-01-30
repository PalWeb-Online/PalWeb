import {computed, onMounted, reactive} from "vue";
import {route} from "ziggy-js";

export function useDeck(props) {
    const data = reactive({
        deck: {},
        isLoading: true
    });

    const description = computed(() => {
        return data.deck.description.length > 190
            ? data.deck.description.substring(0, 187) + '...'
            : data.deck.description;
    });

    async function fetchDeck() {
        if (props.model) {
            data.deck = props.model;
            data.isLoading = false;

        } else {
            try {
                const response = await axios.get(route('decks.get', props.id));
                data.deck = response.data.data;
                data.isLoading = false;

            } catch (error) {
                console.error("Error fetching Deck:", error);
            }
        }
    }

    onMounted(fetchDeck);

    return {data, description};
}
