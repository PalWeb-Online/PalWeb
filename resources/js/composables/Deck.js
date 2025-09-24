import {onMounted, reactive, ref, watch} from "vue";

export function useDeck(props) {
    const deck = reactive({});
    const blurb = ref('');
    const isLoading = ref(true);

    onMounted(() => {
        Object.assign(deck, props.model);

        if (deck.description) {
            blurb.value = deck.description.length > 400
                ? deck.description.substring(0, 397) + '...'
                : deck.description;
        } else {
            blurb.value = `Sadly, ${deck.author?.name} hasn\'t told us anything about this Deck yet.`
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
