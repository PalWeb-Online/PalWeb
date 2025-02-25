<script setup>
import Layout from "../../../Shared/Layout.vue";
import DeckItem from "../../../components/DeckItem.vue";
import Paginator from "../../../Shared/Paginator.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    decks: Object
});
</script>
<template>
    <Head title="Library: Decks"/>
    <div id="app-head">
        <h1>Decks</h1>
    </div>
    <div id="app-body">
        <div class="decks-list">
            <DeckItem v-for="deck in decks.data" :key="deck.id" :model="deck"/>
        </div>
        <Paginator :links="decks.meta.links"/>
    </div>
</template>
