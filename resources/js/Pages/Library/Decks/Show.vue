<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import DeckContainer from "../../../components/DeckContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useDeckViewer} from "../../../composables/decks/useDeckViewer.js";

defineOptions({layout: Layout});

const props = defineProps({
    deckId: {
        type: Number,
        required: true,
    },
});

const {
    deck,
    deckNotFound,
    isLoadingDeck,
    loadDeck,
    reloadDeck,
} = useDeckViewer();

onMounted(() => loadDeck(props.deckId));

watch(
    () => props.deckId,
    () => reloadDeck(props.deckId)
);
</script>

<template>
    <Head :title="deck ? `Library: Decks: ${deck.name}` : 'Library: Decks'"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingDeck"/>
        <DeckContainer v-else-if="deck" :model="deck"/>
        <div v-else-if="deckNotFound" class="loading-state"><p>Unable to load Deck.</p></div>
    </div>
</template>
