import {onMounted, reactive, ref, watch} from "vue";
import {useNotificationStore} from "../stores/NotificationStore.js";

export function useDeck(props) {
    const NotificationStore = useNotificationStore();

    const deck = reactive({});
    const blurb = ref('');
    const isLoading = ref(true);

    const initDeck = (model) => {
        if (!model) return;
        Object.assign(deck, model);

        if (deck.description) {
            blurb.value = deck.description.length > 400
                ? deck.description.substring(0, 397) + '...'
                : deck.description;

        } else {
            blurb.value = `Sadly, ${deck.author?.name} hasn't told us anything about this Deck yet.`;
        }
    };

    onMounted(() => {
        if (props?.model) initDeck(props.model);
        isLoading.value = false;
    });

    watch(() => props?.model, (newDeck) => {
        if (newDeck) initDeck(newDeck);
    }, { deep: true, immediate: true });

    const toggleTerm = async (targetDeck, term) => {
        try {
            const response = await axios.post(route('decks.term.toggle', {
                deck: targetDeck.id,
                term: term.id
            }));

            if (targetDeck.terms) {
                if (response.data.isPresent) {
                    targetDeck.terms.push(term);

                } else {
                    targetDeck.terms = targetDeck.terms.filter(t => t.id !== term.id);
                }
            }

            NotificationStore.addNotification(response.data.message);
            return response.data.isPresent;

        } catch (error) {
            console.error('Deck Toggle Failed', error);
            return null;
        }
    };

    return {deck, blurb, isLoading, toggleTerm};
}
