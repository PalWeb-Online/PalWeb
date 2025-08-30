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

const selectModel = (modelType, index) => {
    if (QuizzerStore.data.model?.id === props.decks[index].id) {
        QuizzerStore.data.model = null;
        QuizzerStore.settings.modelType = null;

    } else {
        QuizzerStore.data.model = props.decks[index];
        QuizzerStore.settings.modelType = modelType;
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
        <div class="quizzer-title featured-title l">Quizzer</div>
        <QuizzerWindow>
            <ScoreStats :model="QuizzerStore.data.model"/>
            <div v-if="QuizzerStore.data.model" class="window-footer">
                <button @click="router.get(route(`quizzer.${QuizzerStore.settings.modelType}`, QuizzerStore.data.model.id))">Select Deck</button>
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
