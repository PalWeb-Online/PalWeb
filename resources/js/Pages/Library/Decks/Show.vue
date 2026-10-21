<script setup>
import {onMounted, ref, watch} from "vue";
import {useRouter} from "vue-router";
import Layout from "../../../Shared/Layout.vue";
import DeckContainer from "../../../components/DeckContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useDeckViewer} from "../../../composables/decks/useDeckViewer.js";
import AppTip from "../../../components/AppTip.vue";

defineOptions({layout: Layout});

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
    deckNotFound,
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
            await router.replace({ name: 'decks.show', params: { deck: randomDeck.id } });
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
    }
);
</script>

<template>

    <LoadingSpinner v-if="isLoadingDeck"/>
    <AppTip v-else-if="deckNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.deck')}) }}</p>
    </AppTip>
    <div v-else-if="deck" id="app-body">
        <DeckContainer :model="deck"/>
    </div>
</template>
