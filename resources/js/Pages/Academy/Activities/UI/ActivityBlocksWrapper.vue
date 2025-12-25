<script setup>
import TextBlock from "../../Lessons/UI/TextBlock.vue";
import TableBlock from "../../Lessons/UI/TableBlock.vue";
import MatchExercisesBlock from "../../Lessons/UI/MatchExercisesBlock.vue";
import AudioBlock from "../../Lessons/UI/AudioBlock.vue";
import InputExercisesBlock from "../../Lessons/UI/InputExercisesBlock.vue";
import SelectExercisesBlock from "../../Lessons/UI/SelectExercisesBlock.vue";

const props = defineProps({
    blocks: {type: Object, required: true},
    results: {type: Array, default: null}
});
</script>

<template>
    <div class="activity-blocks-wrapper">
        <template v-for="block in blocks">
            <TextBlock v-if="block.type === 'text'" :block="block"/>
            <AudioBlock v-if="block.type === 'audio'" :block="block"/>
            <TableBlock v-if="block.type === 'table'" :table="block"/>

            <template v-if="block.type === 'exercises'">
                <InputExercisesBlock v-if="block.exerciseType === 'input'" :block="block" :results="results"/>
                <MatchExercisesBlock v-if="block.exerciseType === 'match'" :block="block" :results="results"/>
                <SelectExercisesBlock v-if="block.exerciseType === 'select'" :block="block" :results="results"/>
            </template>
        </template>
    </div>
</template>
