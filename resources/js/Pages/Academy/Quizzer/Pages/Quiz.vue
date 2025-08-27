<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {computed, ref} from "vue";
import QuestionItem from "../UI/QuestionItem.vue";
import AppTip from "../../../../components/AppTip.vue";
import AppButton from "../../../../components/AppButton.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import ToggleSingle from "../../../../components/ToggleSingle.vue";

const QuizzerStore = useQuizzerStore();

const isValidRequest = computed(() => {
    return !QuizzerStore.quiz.some(question => !question.selection);
});

const showInflections = ref(false);
const showTranslit = ref(false);
</script>
<template>
    <div class="window-container">
        <div class="window-section-head">
            <h1>Options</h1>
        </div>
        <div class="quiz-settings-wrapper" style="justify-content: space-around">
            <ToggleSingle v-model="showTranslit" label="Show Transcription"/>
            <ToggleSingle v-model="showInflections" label="Show Inflections"/>
        </div>
    </div>
    <button @click="router.get(route(`quizzer.${QuizzerStore.data.quizType}`, QuizzerStore.data.model.id))">
        Return to Setup
    </button>
    <AppTip>
        <p>Select the meaning of the Arabic term in English.</p>
    </AppTip>
    <div class="quiz-container">
        <QuestionItem v-for="(question, index) in QuizzerStore.quiz" :key="index"
                      :question="question"
                      :index="index"
                      :showTranslit="showTranslit"
                      :showInflections="showInflections"
        />
    </div>
    <AppButton :disabled="!isValidRequest" @click="QuizzerStore.submitQuiz" label="Submit"/>
</template>
