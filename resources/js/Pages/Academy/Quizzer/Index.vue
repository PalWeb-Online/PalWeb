<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {onMounted, ref} from "vue";
import DeckFlashcard from "../../../components/DeckFlashcard.vue";
import {router} from "@inertiajs/vue3";
import ScoreStats from "../../../components/ScoreStats.vue";
import {useQuizzerStore} from "./Stores/QuizzerStore.js";
import QuizzerWindow from "./UI/QuizzerWindow.vue";

const QuizzerStore = useQuizzerStore();

defineOptions({
    layout: Layout
});

const props = defineProps({
    decks: Array
});

const selectModel = (quizType, index) => {
    if (QuizzerStore.data.model?.id === props.decks[index].id) {
        QuizzerStore.data.quizType = null;
        QuizzerStore.data.model = null;

    } else {
        QuizzerStore.data.quizType = quizType;
        QuizzerStore.data.model = props.decks[index];
    }
};

onMounted(() => {
    QuizzerStore.reset();
    QuizzerStore.data.step = 'select';
});
</script>
<template>
    <Head title="Academy: Quizzer"/>
    <div id="app-body">
        <QuizzerWindow>
            <p>(Score saving & history will be available by the end of September 2025.)</p>
            <ScoreStats :model="false"/>
            <div v-if="QuizzerStore.data.model" class="window-footer">
                <button @click="router.get(route(`quizzer.${QuizzerStore.data.quizType}`, QuizzerStore.data.model.id))">Select Deck</button>
            </div>
        </QuizzerWindow>

        <div id="dm-select">
            <div class="deck-item-grid">
                <DeckFlashcard v-for="(deck, index) in decks" :key="deck.id" :model="deck"
                               :disabled="!deck.terms.length"
                               :active="QuizzerStore.data.model?.id === deck.id"
                               @flip="selectModel('deck', index)"
                />
            </div>
        </div>
    </div>
</template>
