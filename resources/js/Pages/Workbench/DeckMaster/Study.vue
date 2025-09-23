<script setup>
import Layout from "../../../Shared/Layout.vue";
import {useDeckStudyStore} from "./Stores/DeckStudyStore.js";
import Settings from "./Pages/Settings.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onBeforeUnmount, onMounted, watch} from "vue";
import Practice from "./Pages/Practice.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    deck: Object,
});

const DeckStudyStore = useDeckStudyStore();

onMounted(() => {
    DeckStudyStore.reset();
    DeckStudyStore.data.deck = props.deck;
});

onBeforeUnmount(() => {
    DeckStudyStore.reset();
});

watch(() => props.deck, (newDeck) => {
    DeckStudyStore.data.deck = newDeck;
}, {
    deep: true
});

</script>
<template>
    <Head title="Deck Master: Study"/>
    <div id="app-body">
        <Settings v-if="DeckStudyStore.data.step === 'settings'"/>
        <Practice v-if="DeckStudyStore.data.step === 'practice'"/>
        <Quiz v-if="DeckStudyStore.data.step === 'quiz'"/>
        <Results v-if="DeckStudyStore.data.step === 'results'"/>
    </div>
</template>
