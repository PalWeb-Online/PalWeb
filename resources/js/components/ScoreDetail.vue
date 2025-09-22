<script setup>
import AnswerItem from "../Pages/Academy/Quizzer/UI/AnswerItem.vue";
import {computed} from "vue";
import {useQuizzerStore} from "../Pages/Academy/Quizzer/Stores/QuizzerStore.js";

const QuizzerStore = useQuizzerStore();

const props = defineProps({
    score: Object,
});

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    maximumFractionDigits: 0,
});

const scoreMessage = computed(() => {
    if (props.score.score === 1) {
        return "Flawless!";
    }
    if (props.score.score >= 0.85) {
        return "Impressive!";
    }
    if (props.score.score >= 0.7) {
        return "You’re getting there!";
    }
    if (props.score.score >= 0.5) {
        return "Off to a great start!";
    }
    return "Better keep practicing!";
});

const quizType = computed(() => {
    switch (props.score.settings.quizType) {
        case 'term-gloss':
            return 'Glosses';
        case 'term-inflection':
            return 'Inflections';
        case 'sentence-term':
            return 'Sentences';
    }
})
</script>
<template>
    <div class="score-metadata">
        <div class="score-metadata-row">
            <div style="font-weight: 700">Quiz Type</div>
            <div>{{ quizType }}</div>
        </div>
        <div class="score-metadata-row" v-for="(option, key) in score.settings.options">
            <div>{{ key }}</div>
            <div>{{ option }}</div>
        </div>
        <div v-if="score.id" style="font-size: 1.4rem; font-style: italic; text-align: right">
            Quizzed on {{ score.created_at }}.
        </div>
    </div>
    <div class="quiz-results">
        <div class="score-figure featured-title">
            <div>{{ formatter.format(score.score) }}</div>
            <div v-if="!score.id && QuizzerStore.score.score > QuizzerStore.data.model?.stats.highest"
                 class="quiz-results-callout">new record!
            </div>
        </div>
        <div class="score-feedback">
            <div>{{ scoreMessage }}</div>
            <div style="font-weight: 700">
                You answered
                {{ score.results.filter(q => q.correct).length }}
                out of
                {{ score.results.length }}
                questions correctly.
            </div>
            <div>Review your answers below.</div>
        </div>
    </div>
    <div class="quiz-answer-array">
        <AnswerItem v-for="(question, index) in score.results" :key="index"
                    :question="question"
                    :index="index"/>
    </div>
</template>
