import {reactive, ref, watch} from 'vue';
import {useNotifier} from '../useNotifier.js';
import {useRouter} from 'vue-router';
import {useResourceDelete} from '../resources/useResourceDelete.js';
import {useI18n} from 'vue-i18n';

export function useDeck(props = {}, options = {}) {
    const {
        t,
        locale,
    } = useI18n();
    const router = useRouter();
    const Notifier = useNotifier();

    const {
        isDeleting: isDeletingDeck,
        deleteResource: deleteDeck,
    } = useResourceDelete({
        label: 'deck',
        routeBase: 'decks',
        onDeleteSuccess: () => {
            router.push({ name: 'decks.index' });
        },
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
            blurb.value = t('components.deck.description-placeholder', {
                author: locale.value === 'ar' ? deck.author.ar_name : deck.author.name,
            });
        }
    };

    const loadTerms = async () => {
        if (!deck.id || hasLoadedTerms.value || isLoadingTerms.value) return;

        isLoadingTerms.value = true;
        loadTermsError.value = false;

        try {
            const response = await axios.get(`/api/decks/${deck.id}`);
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
    }, { immediate: true });

    const toggleTerm = async (targetDeck, term) => {
        try {
            const response = await axios.post(`/api/decks/${targetDeck.id}/terms/${term.id}/toggle`);

            if (targetDeck.terms) {
                if (response.data.isPresent) {
                    targetDeck.terms.push(term);

                } else {
                    targetDeck.terms = targetDeck.terms.filter(t => t.id !== term.id);
                }
            }

            Notifier.notify({
                key: 'forms.notifications.model-' + (response.data.isPresent ? 'added' : 'removed'),
                params: {
                    model: t('actions.models.term'),
                    target: t('actions.models.deck'),
                },
            });

            return response.data.isPresent;

        } catch (error) {
            console.error('Deck Toggle Failed', error);
            return null;
        }
    };

    const copyDeck = async (targetDeck = deck) => {
        if (!confirm(t('deck.notifications.copy-confirm'))) return;

        const { data } = await axios.post(`/api/decks/${targetDeck.id}/copy`);

        if (data.success) {
            Notifier.notify({
                key: 'deck.notifications.copy-success',
            });
            router.push({
                name: 'decks.show',
                params: { deck: data.deckId },
            });
        }
    };

    const copyLink = (targetDeck = deck) => {
        const path = router.resolve({
            name: 'decks.show',
            params: { deck: targetDeck.id },
        }).href;

        const url = new URL(path, window.location.origin).href;

        navigator.clipboard.writeText(url).then(function () {
            Notifier.notify({
                key: 'deck.notifications.copy-link-success',
            });

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
