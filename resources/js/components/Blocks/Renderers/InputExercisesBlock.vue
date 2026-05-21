<script setup>
import {useExerciseBlock} from "../../../composables/useExerciseBlock.js";
import DialogLine from "../../Charts/DialogLine.vue";
import ExerciseItemPrompts from "./ExerciseItemPrompts.vue";
import ExercisesBlockPrompts from "./ExercisesBlockPrompts.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    ActivitySession,
    processedItems,
} = useExerciseBlock(props);
</script>

<template>
    <div class="block--exercises">
        <h2>{{ block.exerciseType }}</h2>
        <ExercisesBlockPrompts :block="block"/>
        <div v-if="block.examples" v-for="ex in block.examples" class="dialog-body">
            <div class="featured-title s">example</div>
            <DialogLine speaker="سؤال" :ar="ex.prompt"/>
            <DialogLine speaker="جواب" :ar="ex.answer" align="ltr"/>
        </div>
        <template v-for="item in processedItems">
            <div class="exercise--input" :class="{
                    'correct': ActivitySession.isViewingResults && item.correct,
                    'incorrect': ActivitySession.isViewingResults && !item.correct
                }">
                <ExerciseItemPrompts :exercise="item" :isViewingResults="ActivitySession.isViewingResults"/>

                <input type="text" placeholder="جواب"
                       :disabled="ActivitySession.isViewingResults"
                       :value="ActivitySession.isViewingResults ? item.response : ActivitySession.getExerciseById(item.id)?.response"
                       @input="ActivitySession.getExerciseById(item.id).response = $event.target.value"
                >
                <div class="exercise--input-answers" v-if="ActivitySession.isViewingResults && !item.correct">
                    <div>
                        ->
                        <span v-for="answer in item.answers">
                                {{ answer }}
                            </span>
                    </div>
                    <button v-if="ActivitySession.activity"
                            type="button" @click="ActivitySession.markCorrect(item.id)">Mark as Correct
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped lang="scss">
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
