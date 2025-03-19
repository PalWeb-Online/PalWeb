<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckContainer from "../../../components/DeckContainer.vue";
import {route} from 'ziggy-js';

defineOptions({
    layout: Layout
});

defineProps({
    deck: Object
});
</script>
<template>
    <Head :title="`Library: Decks: ${deck.name}`"/>
    <div id="app-head">
        <h1>Decks</h1>
    </div>
    <div id="app-body">
        <Link :href="route('decks.index')"><- to All</Link>
        <DeckContainer :model="deck"/>
    </div>
</template>
