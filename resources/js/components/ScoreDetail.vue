<script setup>
import AnswerItem from "../Pages/Academy/Quizzer/UI/AnswerItem.vue";
import {computed} from "vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";

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

const deleteScore = () => {
    if (!confirm('Are you sure you want to delete this Score?')) return;

    router.delete(route('scores.destroy', props.score.id));
};
</script>
<template>
    <div class="score-metadata">
        <div class="score-metadata-row">
            <div style="font-weight: 700">Quiz Type</div>
            <div>{{ score.settings.typeInput ? 'Inflections' : 'Glosses' }}</div>
        </div>
        <div class="score-metadata-row" v-for="(option, key) in score.settings.options">
            <div>{{ key }}</div>
            <div>{{ option }}</div>
        </div>
        <!--        todo: only in Show -->
        <div style="font-size: 1.4rem; font-style: italic; text-align: right">Quizzed on {{ score.created_at }}.</div>
    </div>
    <!--        todo: only in Show -->
    <button @click="deleteScore">Delete Score</button>
    <div class="quiz-results">
        <div class="score-figure featured-title">
            <div>{{ formatter.format(score.score) }}</div>
            <!--            todo: only in Results -->
            <div v-if="QuizzerStore.score > QuizzerStore.data.model.stats.highest"
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
