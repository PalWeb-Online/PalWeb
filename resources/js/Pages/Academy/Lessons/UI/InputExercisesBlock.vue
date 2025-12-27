<script setup>
import DialogLine from "../../../../components/Charts/DialogLine.vue";
import {useExerciseBlock} from "../../../../composables/useExerciseBlock.js";

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
        <div class="featured-title l">{{ block.exerciseType }}</div>
        <p>Answer the prompt with a complete sentence.</p>
        <div v-if="block.examples" v-for="ex in block.examples" class="dialog-body">
            <div class="featured-title s">example</div>
            <DialogLine speaker="سؤال" :ar="ex.prompt"/>
            <DialogLine speaker="جواب" :ar="ex.answer" align="ltr"/>
        </div>
        <template v-for="item in processedItems">
            <div v-if="item.images.length" class="exercise-images">
                <img v-for="imgUrl in item.images" :src="imgUrl" alt="Reference Image">
            </div>
            <div class="exercise--input" :class="{
                    'correct': isViewingResults && item.correct,
                    'incorrect': isViewingResults && !item.correct
                }">
                <div class="exercise-prompt">
                        <span v-if="isViewingResults" class="material-symbols-rounded"
                              :class="{ 'correct': item.correct }">
                            {{ item.correct ? 'check_circle' : 'cancel' }}
                        </span>
                    <p>{{ item.prompt }}</p>
                </div>
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
