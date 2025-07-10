<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckContainer from "../../../components/DeckContainer.vue";

defineOptions({
    layout: Layout
});

defineProps({
    deck: Object
});
</script>
<template>
    <Head :title="`Library: Decks: ${deck.name}`"/>
    <div id="app-body">
        <DeckContainer :model="deck"/>
    </div>
</template>
