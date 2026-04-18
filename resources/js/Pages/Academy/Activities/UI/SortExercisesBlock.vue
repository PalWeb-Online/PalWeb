<script setup>
import {useExerciseBlock} from "../../../../composables/useExerciseBlock.js";
import ExerciseItemPrompts from "./ExerciseItemPrompts.vue";
import ExercisesBlockPrompts from "./ExercisesBlockPrompts.vue";
import Draggable from "vuedraggable";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    ActivityStore,
    isViewingResults,
    processedItems,
} = useExerciseBlock(props);

const handleDragEnd = (exerciseId) => {
    const exercise = ActivityStore.getExerciseById(exerciseId);
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
                    'correct': isViewingResults && ex.correct,
                    'incorrect': isViewingResults && !ex.correct
                }">
                <ExerciseItemPrompts :exercise="ex" :isViewingResults="isViewingResults"/>

                <Draggable
                    v-if="!isViewingResults"
                    class="exercise--sort-items"
                    :list="ActivityStore.getExerciseById(ex.id)?.shuffledItems"
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

    &:not(.results) .sort-item {
        cursor: grab;
    }

    .sort-item {
        color: white;
        background: var(--color-medium-secondary);
        box-shadow: 0 0.4rem 0 0 var(--color-dark-primary);
        padding: 0.4rem 1.6rem;
        border-radius: 0.8rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        font-family: var(--mono-font), monospace;
        font-size: 2.0rem;
        font-weight: 700;
        user-select: none;
    }

    .sort-items-wrapper {
        display: flex;
        flex-flow: row wrap;
        align-items: center;
        gap: 1.6rem;

        &.incorrect {
            opacity: 0.5;
        }
    }
}
</style>
