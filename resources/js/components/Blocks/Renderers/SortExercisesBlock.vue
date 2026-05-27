<script setup>
import {useExerciseBlock} from "../../../composables/useExerciseBlock.js";
import ExerciseItemPrompts from "./ExerciseItemPrompts.vue";
import ExercisesBlockPrompts from "./ExercisesBlockPrompts.vue";
import Draggable from "vuedraggable";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    ActivitySession,
    processedItems,
} = useExerciseBlock(props);

const handleDragEnd = (exerciseId) => {
    const exercise = ActivitySession.getExerciseById(exerciseId);
    if (!exercise) return;

    exercise.response = exercise.shuffledItems;
}
</script>

<template>
    <div class="block--exercises">
        <h2>{{ block.exerciseType }}</h2>
        <ExercisesBlockPrompts :block="block"/>

        <template v-for="ex in processedItems">
            <div class="exercise--sort" :class="{
                    'correct': ActivitySession.isViewingResults && ex.correct,
                    'incorrect': ActivitySession.isViewingResults && !ex.correct
                }">
                <ExerciseItemPrompts
                    :exercise="ex" :isViewingResults="ActivitySession.isViewingResults"/>

                <Draggable
                    v-if="!ActivitySession.isViewingResults"
                    class="exercise--sort-items"
                    :list="ActivitySession.getExerciseById(ex.id)?.shuffledItems"
                    item-key="id"
                    @end="handleDragEnd(ex.id)"
                >
                    <template #item="{ element: item }">
                        <div class="sort-item">{{ item.text }}</div>
                    </template>
                </Draggable>

                <div v-else class="exercise--sort-items results">
                    <div class="sort-items-wrapper"
                         :class="{
                                    'correct': ex.correct,
                                    'incorrect': !ex.correct,
                                }"
                    >
                        <div v-for="(item, i) in ex.response" :key="item.id ?? i" class="sort-item">
                            {{ item.text }}
                        </div>
                    </div>
                    <div v-if="!ex.correct" class="sort-items-wrapper correct">
                        ->
                        <div v-for="(item, i) in ex.items" :key="item.id ?? i" class="sort-item">
                            {{ item.text }}
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped lang="scss">
.exercise--sort-items {
    display: grid;
    justify-items: start;
    gap: 1.6rem;
    font-family: var(--mono-font), monospace;

    &:not(.results) .sort-item {
        cursor: grab;
    }

    .sort-item {
        color: white;
        background: var(--color-medium-secondary);
        padding: 0.25em 1em 0.33em;
        border-radius: 0.75em;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        font-size: 2.0rem;
        font-weight: 700;
        user-select: none;
    }

    .sort-items-wrapper {
        display: flex;
        flex-flow: row wrap;
        align-items: center;
        gap: 1.2rem;
        font-size: 2.0rem;
        font-weight: 700;
        color: var(--color-medium-secondary);

        &.incorrect {
            opacity: 0.5;
        }
    }
}
</style>
