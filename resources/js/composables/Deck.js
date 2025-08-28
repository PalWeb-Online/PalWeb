import {onMounted, reactive, ref, watch} from "vue";
import {Howl} from "howler";

export function useDeck(props) {

    const deck = reactive({});
    const blurb = ref(null);
    const isLoading = ref(true);

    onMounted(() => {
        Object.assign(deck, props.model);

        if (deck.description) {
            blurb.value = deck.description.length > 190
                ? deck.description.substring(0, 187) + '...'
                : deck.description;
        }

        isLoading.value = false;
    });

    watch(() => props.model,
        (newDeck) => {
            Object.assign(deck, newDeck);
        },
        {deep: true}
    );

    return {deck, blurb, isLoading};
}
