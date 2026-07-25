<script setup>
import {computed} from "vue";
import {useScoreManager} from "../composables/useScoreManager.js";
import {useI18n} from "vue-i18n";

const props = defineProps({
    model: {type: Object, required: false},
    score: {type: Object, required: true},
});

const {getScoreStats} = useScoreManager();
const {t} = useI18n();

const scoreMessage = computed(() => {
    if (props.score.score >= 1) {
        return t('components.score.feedback.highest');
    }
    if (props.score.score >= 0.85) {
        return t('components.score.feedback.high');
    }
    if (props.score.score >= 0.7) {
        return t('components.score.feedback.medium');
    }
    if (props.score.score >= 0.5) {
        return t('components.score.feedback.low');
    }
    return t('components.score.feedback.lowest');
});
</script>
<template>
    <div class="score-metadata" v-if="score.id || score.scorable_type === 'deck'">
        <div class="score-metadata-row" v-if="score.scorable_type === 'deck'">
            <div>
                <span style="font-weight: 700">{{ $t('components.score.quiz.type') }}</span>
                <span style="text-transform: capitalize">
                    {{ $t(`components.score.quiz.types.${score.settings.quizType}`) }}
                </span>
            </div>
            <template v-for="(option, key) in score.settings.options">
                <div v-if="key === 'strictTerms' && score.settings.quizType === 'glosses'">
                    <span>{{ $t(`components.score.quiz.options.${key}`) }}</span>
                    <span>{{ $t(`components.common.boolean.${option}`) }}</span>
                </div>
                <div v-if="key === 'strictGloss' && ['glosses', 'sentences'].includes(score.settings.quizType)">
                    <span>{{ $t(`components.score.quiz.options.${key}`) }}</span>
                    <span>{{ $t(`components.common.boolean.${option}`) }}</span>
                </div>
                <div v-if="key === 'withTranslation' && score.settings.quizType === 'sentences'">
                    <span>{{ $t(`components.score.quiz.options.${key}`) }}</span>
                    <span>{{ $t(`components.common.boolean.${option}`) }}</span>
                </div>
            </template>
        </div>
        <div v-if="score.id" style="font-size: 1.4rem; font-style: italic; text-align: right">
            {{ $t('components.score.quizzed-on', {date: score.created_at}) }}
        </div>
    </div>
    <div class="quiz-results">
        <div class="score-figure featured-title">
            <div>{{ getScoreStats(score).formatted }}</div>
            <div v-if="!score.id && score.score > model?.stats.highest" class="quiz-results-callout">
                {{ $t('components.score.new-record') }}
            </div>
        </div>
        <div class="score-feedback">
            <div>{{ scoreMessage }}</div>
            <div style="font-weight: 700">
                {{
                    $t('components.score.answered-correctly', {
                        correct: getScoreStats(score).correct,
                        total: getScoreStats(score).total
                    })
                }}
            </div>
            <div>{{ $t('components.score.review-answers') }}</div>
        </div>
    </div>
    <slot/>
</template>

<style scoped lang="scss">
.score-metadata {
    background: var(--color-accent-light);
    padding: 1.6rem;
    color: var(--color-dark-primary);
    display: grid;
    gap: 0.8rem;

    .score-metadata-row {
        display: flex;
        gap: 1.6rem;

        & > div {
            display: flex;
            gap: 0.8rem;
        }

        & > div > *:first-child {
            font-weight: 700;
        }
    }
}

.quiz-results {
    margin: 3.2rem 3.2rem 3.2rem;
    display: flex;
    flex-flow: row wrap;
    gap: 1.6rem 3.2rem;
    align-items: center;

    .score-figure {
        font-size: 12.8rem;
        animation: bounceIn 0.5s;
        position: relative;

        .quiz-results-callout {
            position: absolute;
            bottom: -0.6rem;
            left: 0.8rem;
            font-size: 2.4rem;
            color: var(--color-accent-medium);
        }
    }

    .score-feedback {
        flex-grow: 1;
        display: grid;
        gap: 0.4rem;
        color: var(--color-dark-primary);

        & > *:nth-child(1) {
            font-family: var(--head-font);
            font-size: 2.4rem;
            font-weight: 700;
            color: var(--color-medium-primary);
        }
    }
}
</style>
