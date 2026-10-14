<script setup>
import Layout from "../../../Shared/Layout.vue";
import {useDeckStudyStore} from "./Stores/DeckStudyStore.js";
import Settings from "./Pages/Settings.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onBeforeUnmount, onMounted} from "vue";
import Practice from "./Pages/Practice.vue";
import {route} from "ziggy-js";

defineOptions({
    layout: Layout
});

const props = defineProps({
    deckId: Number,
});

const DeckStudyStore = useDeckStudyStore();

onMounted(async () => {
    DeckStudyStore.reset();
    const {data} = await axios.get(route('deck-master.study.get', props.deckId));
    DeckStudyStore.data.deck = data.deck;
});

onBeforeUnmount(() => {
    DeckStudyStore.reset();
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
