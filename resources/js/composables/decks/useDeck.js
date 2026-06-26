import {reactive, ref, watch} from "vue";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {useResourceDelete} from "../resources/useResourceDelete.js";

export function useDeck(props = {}, options = {}) {
    const NotificationStore = useNotificationStore();

    const {
        isDeleting: isDeletingDeck,
        deleteResource: deleteDeck,
    } = useResourceDelete({
        routeBase: 'decks',
        label: 'Deck',
    });

    const deck = reactive({});
    const blurb = ref('');
    const isLoading = ref(true);
    const isLoadingTerms = ref(false);
    const hasLoadedTerms = ref(false);
    const loadTermsError = ref(false);

    const hasTerms = (model) => {
        return Array.isArray(model?.terms);
    };

    const replaceDeck = (model) => {
        Object.keys(deck).forEach(key => delete deck[key]);
        Object.assign(deck, {
            terms: [],
            scores: [],
            ...model,
        });
    };

    const initDeck = (model) => {
        if (!model) return;
        replaceDeck(model);
        hasLoadedTerms.value = hasTerms(model);

        if (deck.description) {
            blurb.value = deck.description.length > 400
                ? deck.description.substring(0, 397) + '...'
                : deck.description;

        } else {
            blurb.value = `Sadly, ${deck.author?.name} hasn't told us anything about this Deck yet.`;
        }
    };

    const loadTerms = async () => {
        if (!deck.id || hasLoadedTerms.value || isLoadingTerms.value) return;

        isLoadingTerms.value = true;
        loadTermsError.value = false;

        try {
            const response = await axios.get(route('api.decks.get.terms', deck.id));
            initDeck(response.data.deck);
        } catch (error) {
            console.error('Deck Detail Load Failed', error);
            loadTermsError.value = true;
        } finally {
            isLoadingTerms.value = false;
        }
    };

    watch(() => props?.model, (newDeck) => {
        if (newDeck) {
            initDeck(newDeck);

            if (options.loadTerms) {
                loadTerms();
            }
        }

        isLoading.value = false;
    }, {immediate: true});

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

    const copyDeck = (targetDeck = deck) => {
        if (!confirm('Are you sure you want to create a copy of this Deck?')) return;

        router.post(route('decks.copy', targetDeck.id));
    };

    const copyLink = (targetDeck = deck) => {
        navigator.clipboard.writeText(route('decks.show', targetDeck.id)).then(function () {
            alert('Copied to clipboard.');
        }, function (err) {
            alert('Could not copy text: ', err);
        });
    };

    return {
        deck,
        blurb,
        isLoading,
        isLoadingTerms,
        hasLoadedTerms,
        loadTermsError,
        isDeletingDeck,
        loadTerms,
        toggleTerm,
        copyDeck,
        copyLink,
        deleteDeck,
    };
}
