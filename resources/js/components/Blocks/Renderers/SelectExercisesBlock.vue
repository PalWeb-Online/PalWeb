<script setup>
import {useExerciseBlock} from "../../../../composables/useExerciseBlock.js";
import ExerciseItemPrompts from "./ExerciseItemPrompts.vue";
import ExercisesBlockPrompts from "./ExercisesBlockPrompts.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    ActivityStore,
    isViewingResults,
    processedItems,
} = useExerciseBlock(props);

const selectOption = (itemId, optionId) => {
    const exercise = ActivityStore.getExerciseById(itemId);

    exercise.response === optionId
        ? exercise.response = null
        : exercise.response = optionId
}
</script>

<template>
    <div class="block--exercises">
        <h2>{{ block.exerciseType }}</h2>
        <ExercisesBlockPrompts :block="block"/>
        <template v-for="item in processedItems">
            <div class="exercise--select">
                <ExerciseItemPrompts :exercise="item" :isViewingResults="isViewingResults"/>

                <div class="exercise--select-options">
                    <template v-for="option in item.displayOptions" :key="option.id">
                        <button v-if="!isViewingResults"
                                :class="{ 'selected': ActivityStore.getExerciseById(item.id)?.response === option.id }"
                                @click="selectOption(item.id, option.id)"
                        >
                            {{ option.text }}
                        </button>
                        <button v-else disabled
                                :class="{
                                    'incorrect': item.response === option.id && item.answerId !== option.id,
                                    'correct': item.answerId === option.id,
                                }"
                        >
                            {{ option.text }}
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>
