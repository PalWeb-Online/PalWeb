<script setup>
import DialogLine from "../../../../components/Charts/DialogLine.vue";
import {useExerciseBlock} from "../../../../composables/useExerciseBlock.js";
import ExercisePrompts from "./ExercisePrompts.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    ActivityStore,
    isViewingResults,
    processedItems,
} = useExerciseBlock(props);
</script>

<template>
    <div class="block--exercises">
        <h2>{{ block.exerciseType }}</h2>
        <p>Answer the prompt with a complete sentence.</p>
        <div v-if="block.examples" v-for="ex in block.examples" class="dialog-body">
            <div class="featured-title s">example</div>
            <DialogLine speaker="سؤال" :ar="ex.prompt"/>
            <DialogLine speaker="جواب" :ar="ex.answer" align="ltr"/>
        </div>
        <template v-for="item in processedItems">
            <div class="exercise--input" :class="{
                    'correct': isViewingResults && item.correct,
                    'incorrect': isViewingResults && !item.correct
                }">
                <ExercisePrompts :exercise="item" :isViewingResults="isViewingResults"/>

                <input type="text" placeholder="جواب"
                       :disabled="isViewingResults"
                       :value="isViewingResults ? item.response : ActivityStore.getExerciseById(item.id)?.response"
                       @input="ActivityStore.getExerciseById(item.id).response = $event.target.value"
                >
                <div class="exercise--input-answers" v-if="isViewingResults && !item.correct">
                    <div>
                        ->
                        <span v-for="answer in item.answers">
                                {{ answer }}
                            </span>
                    </div>
                    <button v-if="ActivityStore.data.activity"
                            type="button" @click="ActivityStore.markCorrect(item.id)">Mark as Correct
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
        background: white;
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
