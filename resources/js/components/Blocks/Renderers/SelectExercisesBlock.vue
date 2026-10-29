<script setup>
import {useExerciseBlock} from "../../../composables/useExerciseBlock.js";
import ExerciseItemPrompts from "./ExerciseItemPrompts.vue";
import ExercisesBlockPrompts from "./ExercisesBlockPrompts.vue";
import AppTip from "../../AppTip.vue";

const props = defineProps({
    block: {type: Object, required: true},
});

const {
    isViewingResults,
    getExerciseById,
    processedItems,
} = useExerciseBlock(props);

const selectOption = (itemId, optionId) => {
    if (isViewingResults.value) return;

    const exercise = getExerciseById(itemId);
    if (!exercise) return;

    exercise.response === optionId
        ? exercise.response = null
        : exercise.response = optionId
}
</script>

<template>
    <div class="block--exercises">
        <h2>{{ $t(`exercise.type.${block.exerciseType}`) }}</h2>
        <ExercisesBlockPrompts :block="block"/>
        <template v-for="item in processedItems">
            <div class="exercise--select">
                <ExerciseItemPrompts :exercise="item" :isViewingResults="isViewingResults"/>

                <div class="exercise--select-options">
                    <template v-for="option in item.displayOptions" :key="option.id">
                        <button v-if="!isViewingResults"
                                :class="{ 'selected': getExerciseById(item.id)?.response === option.id }"
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
                <AppTip v-if="isViewingResults && item.tip">
                    <p>{{ item.tip }}</p>
                </AppTip>
            </div>
        </template>
    </div>
</template>
