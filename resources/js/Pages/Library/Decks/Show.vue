<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import DeckContainer from "../../../components/DeckContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useDeckViewer} from "../../../composables/decks/useDeckViewer.js";
import AppTip from "../../../components/AppTip.vue";

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

    <LoadingSpinner v-if="isLoadingDeck"/>
    <AppTip v-else-if="deckNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.deck')}) }}</p>
    </AppTip>
    <div v-else-if="deck" id="app-body">
        <DeckContainer :model="deck"/>
    </div>
</template>
