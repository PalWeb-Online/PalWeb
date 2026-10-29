<script setup>
import {useExerciseBlock} from "../../../composables/useExerciseBlock.js";
import DialogLine from "../../Charts/DialogLine.vue";
import ExerciseItemPrompts from "./ExerciseItemPrompts.vue";
import ExercisesBlockPrompts from "./ExercisesBlockPrompts.vue";
import AppTip from "../../AppTip.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    activity,
    isViewingResults,
    markCorrect,
    getExerciseResponse,
    setExerciseResponse,
    processedItems,
} = useExerciseBlock(props);
</script>

<template>
    <div class="block--exercises">
        <h2>{{ $t(`exercise.type.${block.exerciseType}`) }}</h2>
        <ExercisesBlockPrompts :block="block"/>
        <div v-if="block.examples" v-for="ex in block.examples" class="exercise--input-example">
            <div class="featured-title s">{{ $t('exercise.example') }}</div>
            <DialogLine speaker="سؤال" :ar="ex.prompt"/>
            <DialogLine speaker="جواب" :ar="ex.answer" align="ltr"/>
        </div>
        <div v-for="item in processedItems"
             class="exercise--input" :class="{
                    'correct': isViewingResults && item.correct,
                    'incorrect': isViewingResults && !item.correct
                }">
            <ExerciseItemPrompts :exercise="item" :isViewingResults="isViewingResults"/>

            <input type="text" placeholder="جواب"
                   :disabled="isViewingResults"
                   :value="getExerciseResponse(item)"
                   @input="setExerciseResponse(item.id, $event.target.value)"
            >
            <div class="exercise--input-answers" v-if="isViewingResults && !item.correct">
                <div>
                    ->
                    <span v-for="answer in item.answers">
                                {{ answer }}
                            </span>
                </div>
                <button v-if="activity"
                        type="button" @click="markCorrect(item.id)">Mark as Correct
                </button>
            </div>
            <AppTip v-if="isViewingResults && item.tip">
                <p>{{ item.tip }}</p>
            </AppTip>
        </div>
    </div>
</template>

<style scoped lang="scss">
.exercise--input-example {
    display: grid;
    text-align: start;
    margin: 2.4rem;
    gap: 1.6rem;
    justify-items: right;

    & + .exercise--input {
        margin-block-start: 3.2rem;
    }
}

.exercise--input {
    input {
        direction: rtl;
        font-size: 2.0rem;
        font-weight: 700;
        width: 100%;
        background: var(--color-accent-light);

        &[disabled] {
            opacity: 1;
        }
    }

    &.correct {
        input {
            color: white;
            background: var(--color-medium-secondary);
        }
    }
}

.exercise--input-answers {
    display: flex;
    align-items: center;
    gap: 1.6rem;

    & > div {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        font-family: var(--mono-font), monospace;
        font-size: 2.0rem;
        font-weight: 700;
        color: var(--color-medium-secondary);
    }

    span {
        font-size: 1.6rem;
        padding: 0.8rem 2.4rem;
        border-radius: 6.4rem;
        color: white;
        background: var(--color-medium-secondary);
    }

    button {
        font-size: 1.6rem;
        font-family: var(--body-font), sans-serif;
        font-weight: 700;
        color: var(--color-dark-secondary);

        &:hover {
            text-decoration: underline;
        }
    }
}
</style>
