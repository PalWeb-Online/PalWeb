<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import {computed} from "vue";
import QuestionItem from "../UI/QuestionItem.vue";

const QuizzerStore = useQuizzerStore();

const isValidRequest = computed(() => {
    return !QuizzerStore.quiz.some(question => !question.selection);
});
</script>
<template>
    <div class="quiz-container" style="background: white">
        <div class="quiz-instructions">
            This Quiz has {{QuizzerStore.quiz.length}} questions. The type of Quiz is {{QuizzerStore.data.quizType}}. Select the meaning of the Arabic term in English.
        </div>
        <QuestionItem v-for="(question, index) in QuizzerStore.quiz" :key="index"
                      :question="question" :index="index"/>
    </div>
    <div class="window-footer">
        <button :disabled="!isValidRequest" @click="QuizzerStore.submitQuiz">Submit Quiz</button>
    </div>
</template>
