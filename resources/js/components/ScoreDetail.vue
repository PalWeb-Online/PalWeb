<script setup>
import {computed} from "vue";

const props = defineProps({
    score: {type: Object, required: true},
    model: {type: Object, required: false}
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
</script>
<template>
    <div class="score-metadata">
        <div class="score-metadata-row">
            <div>
                <span style="font-weight: 700">Quiz Type</span>
                <span style="text-transform: capitalize">{{ score.settings.quizType }}</span>
            </div>
            <template v-for="(option, key) in score.settings.options">
                <div v-if="key === 'strictTerms' && score.settings.quizType === 'glosses'">
                    <span>{{ key }}</span>
                    <span>{{ option }}</span>
                </div>
                <div v-if="key === 'strictGloss' && ['glosses', 'sentences'].includes(score.settings.quizType)">
                    <span>{{ key }}</span>
                    <span>{{ option }}</span>
                </div>
                <div v-if="key === 'withTranslation' && score.settings.quizType === 'sentences'">
                    <span>{{ key }}</span>
                    <span>{{ option }}</span>
                </div>
            </template>
        </div>
        <div v-if="score.id" style="font-size: 1.4rem; font-style: italic; text-align: right">
            Quizzed on {{ score.created_at }}.
        </div>
    </div>
    <div class="quiz-results">
        <div class="score-figure featured-title">
            <div>{{ formatter.format(score.score) }}</div>
            <div v-if="!score.id && score.score > model?.stats.highest" class="quiz-results-callout">
                new record!
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
    <slot/>
</template>
