<script setup>
import Layout from "../../../Shared/Layout.vue";
import Settings from "./Pages/Settings.vue";
import Quiz from "./Pages/Quiz.vue";
import Results from "./Pages/Results.vue";
import {onMounted, watch} from "vue";
import Practice from "./Pages/Practice.vue";
import {route} from "ziggy-js";
import {createDeckStudySession, provideDeckStudySession} from "../../../composables/decks/useDeckStudySession.js";

defineOptions({
    layout: Layout
});

const props = defineProps({
    deckId: Number,
});

const DeckStudySession = provideDeckStudySession(createDeckStudySession());

const openDeck = async () => {
    DeckStudySession.reset();
    const {data} = await axios.get(route('deck-master.study.get', props.deckId));
    DeckStudySession.data.deck = data.deck;
};

onMounted(() => {
    void openDeck();
});

watch(() => props.deckId, () => {
    void openDeck();
});
</script>
<template>
    <div id="app-body">
        <Settings v-if="DeckStudySession.data.step === 'settings'"/>
        <Practice v-if="DeckStudySession.data.step === 'practice'"/>
        <Quiz v-if="DeckStudySession.data.step === 'quiz'"/>
        <Results v-if="DeckStudySession.data.step === 'results'"/>
    </div>
</template>
