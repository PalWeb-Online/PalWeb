<script setup>
import AnswerItem from "../Pages/Academy/Quizzer/UI/AnswerItem.vue";
import {computed} from "vue";

const props = defineProps({
    settings: Object,
    score: Number,
    results: Object,
});

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const scoreMessage = computed(() => {
    if (props.score === 1) {
        return "Flawless!";
    }
    if (props.score >= 0.85) {
        return "Impressive!";
    }
    if (props.score >= 0.7) {
        return "You’re getting there!";
    }
    if (props.score >= 0.5) {
        return "Off to a great start!";
    }
    return "Better keep practicing!";
});
</script>
<template>
    <div class="quiz-results">
        <div class="score-figure featured-title">
            <div>{{ formatter.format(score) }}</div>
<!--            <div v-if="QuizzerStore.score > QuizzerStore.data.model.stats.highest"-->
<!--                 class="quiz-results-callout">new record!-->
<!--            </div>-->
        </div>
        <div class="score-feedback">
            <div>{{ scoreMessage }}</div>
            <div style="font-weight: 700">
                You answered
                {{ results.filter(q => q.correct).length }}
                out of
                {{ results.length }}
                questions correctly.
            </div>
            <div>Review your answers below.</div>
        </div>
    </div>
    <div class="quiz-answer-array">
        <AnswerItem v-for="(question, index) in results" :key="index"
                    :question="question"
                    :index="index"/>
    </div>
</template>
