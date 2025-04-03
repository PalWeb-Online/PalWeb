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
        <Link :href="route('decks.index')"><h1>Decks</h1></Link>
    </div>
    <div id="app-body">
        <div class="nav-body">
            <Link :href="route('decks.index')"><- to All</Link>
            <Link :href="route('decks.random')">to Random -></Link>
        </div>
        <DeckContainer :model="deck"/>
    </div>
</template>
