<script setup>
import {useExerciseBlock} from "../../../../composables/useExerciseBlock.js";

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
        <p>Select the most appropriate answer in response to the prompt.</p>
        <template v-for="item in processedItems">
            <div class="exercise--select">
                <div v-if="item.images.length" class="exercise-images">
                    <img v-for="imgUrl in item.images" :src="imgUrl" alt="Reference Image">
                </div>
                <div class="exercise-prompt">
                        <span v-if="isViewingResults" class="material-symbols-rounded"
                              :class="{ 'correct': item.correct }">
                            {{ item.correct ? 'check_circle' : 'cancel' }}
                        </span>
                    <p>{{ item.prompt }}</p>
                </div>
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
