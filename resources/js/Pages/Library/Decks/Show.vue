<script setup>
import {onMounted, ref, watch} from 'vue';
import {useRouter} from 'vue-router';
import DeckContainer from '../../../components/DeckContainer.vue';
import {useDeckViewer} from '../../../composables/decks/useDeckViewer.js';
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const router = useRouter();
const resolvedRandomDeckId = ref(null);

const props = defineProps({
    deckId: {
        type: Number,
        default: null,
    },
    random: {
        type: Boolean,
        default: false,
    },
});

const {
    deck,
    isLoadingDeck,
    loadDeck,
    loadRandomDeck,
    reloadDeck,
    reloadRandomDeck,
} = useDeckViewer();

const openDeck = async (reload = false) => {
    if (props.random) {
        const randomDeck = await (reload ? reloadRandomDeck : loadRandomDeck)();

        if (randomDeck?.id) {
            resolvedRandomDeckId.value = randomDeck.id;
            router.replace({
                name: 'decks.show',
                params: { deck: randomDeck.id },
            });
        }

        return;
    }

    await (reload ? reloadDeck : loadDeck)(props.deckId);
};

onMounted(() => {
    void openDeck();
});

watch(
    () => [props.deckId, props.random],
    ([deckId, random]) => {
        if (!random && deckId === resolvedRandomDeckId.value) {
            resolvedRandomDeckId.value = null;
            return;
        }

        void openDeck(true);
    },
);
</script>

<template>
    <LoadingWrapper :is-loading="isLoadingDeck || !deck">
        <div id="app-body">
            <DeckContainer :model="deck"/>
        </div>
    </LoadingWrapper>
</template>
