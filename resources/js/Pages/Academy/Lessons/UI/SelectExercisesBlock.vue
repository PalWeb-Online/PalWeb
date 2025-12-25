<script setup>
import {useExerciseBase} from "../../../../composables/useExerciseBlock.js";

const props = defineProps({
    block: {type: Object, required: true},
    results: {type: Array, default: null}
});

const {
    ActivityStore,
    getItemState,
    processedItems,
} = useExerciseBase(props);

const selectOption = (itemId, optionId) => {
    const exercise = ActivityStore.getExerciseById(itemId);

    exercise.response === optionId
        ? exercise.response = null
        : exercise.response = optionId
}
</script>

<template>
    <div class="block--exercises">
        <div class="featured-title l">{{ block.exerciseType }}</div>
        <p>Select the most appropriate answer in response to the prompt.</p>
        <template v-for="item in processedItems">
            <div class="exercise--select">
                <div v-if="item.images.length" class="exercise-images">
                    <img v-for="imgUrl in item.images" :src="imgUrl" alt="Reference Image">
                </div>
                <div class="exercise-prompt">
                        <span v-if="!!results" class="material-symbols-rounded"
                              :class="{ 'correct': getItemState(item.id)?.correct }">
                            {{ getItemState(item.id)?.correct ? 'check_circle' : 'cancel' }}
                        </span>
                    <p>{{ item.prompt }}</p>
                </div>
                <div class="exercise--select-options">
                    <template v-for="option in item.displayOptions" :key="option.id">
                        <button v-if="!results"
                                :class="{ 'selected': ActivityStore.getExerciseById(item.id).response === option.id }"
                                @click="selectOption(item.id, option.id)"
                        >
                            {{ option.text }}
                        </button>
                        <button v-else disabled
                                :class="{
                                    'incorrect': getItemState(item.id)?.response === option.id && getItemState(item.id)?.answerId !== option.id,
                                    'correct': getItemState(item.id)?.answerId === option.id,
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
