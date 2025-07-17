<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {ref} from "vue";
import DeckFlashcard from "../../../../components/DeckFlashcard.vue";

const QuizzerStore = useQuizzerStore();

const quizSettings = ref({
    allGlosses: true
});
</script>
<template>
    <div style="background: white">
        <DeckFlashcard v-if="QuizzerStore.data.model" :model="QuizzerStore.data.model"/>
        <div class="field-compound-toggle-wrapper">
            <div class="field-toggle-title">source decoys from</div>
            <div class="field-toggle-wrapper">
                <div :class="quizSettings.allGlosses ? 'active' : ''">all</div>
                <button class="field-toggle" :class="{ active: !quizSettings.allGlosses }"
                        @click="quizSettings.allGlosses = !quizSettings.allGlosses">
                    <div class="field-toggle-slider"></div>
                </button>
                <div :class="!quizSettings.allGlosses ? 'active' : ''">deck</div>
            </div>
        </div>
    </div>
    <div class="window-footer">
        <button @click="QuizzerStore.startQuiz(quizSettings)">Start Quiz</button>
    </div>
</template>
