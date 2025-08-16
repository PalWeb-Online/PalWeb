<script setup>
import {useQuizzerStore} from "../Stores/QuizzerStore.js";
import AppTip from "../../../../components/AppTip.vue";
import QuestionItem from "../UI/QuestionItem.vue";

const QuizzerStore = useQuizzerStore();
</script>
<template>
    <div class="window-section-head">
        <h2>Results</h2>
    </div>
    <div style="background: white">
        <AppTip>
            <p>
                You got a {{ QuizzerStore.score }}/{{ QuizzerStore.quiz.length }}.
            </p>
        </AppTip>
        <QuestionItem v-for="(question, index) in QuizzerStore.quiz" :key="index"
                      :question="question" :index="index" show-results/>
    </div>
    <div class="window-footer">
        <button @click="QuizzerStore.startOver()">new quiz</button>
    </div>
</template>
