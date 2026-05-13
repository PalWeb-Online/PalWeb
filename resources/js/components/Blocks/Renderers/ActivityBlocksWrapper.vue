<script setup>
import TextBlock from "../../Lessons/UI/TextBlock.vue";
import TableBlock from "../../Lessons/UI/TableBlock.vue";
import ImageBlock from "../../Lessons/UI/ImageBlock.vue";
import AudioBlock from "../../Lessons/UI/AudioBlock.vue";
import InputExercisesBlock from "./InputExercisesBlock.vue";
import MatchExercisesBlock from "./MatchExercisesBlock.vue";
import SelectExercisesBlock from "./SelectExercisesBlock.vue";
import SortExercisesBlock from "./SortExercisesBlock.vue";

const props = defineProps({
    blocks: {type: Object, required: true},
});
</script>

<template>
    <div class="activity-blocks-wrapper">
        <template v-for="block in blocks">
            <TextBlock v-if="block.type === 'text'" :block="block"/>
            <ImageBlock v-if="block.type === 'image'" :block="block"/>
            <AudioBlock v-if="block.type === 'audio'" :block="block"/>
            <TableBlock v-if="block.type === 'table'" :table="block"/>

            <template v-if="block.type === 'exercises'">
                <InputExercisesBlock v-if="block.exerciseType === 'input'" :block="block"/>
                <MatchExercisesBlock v-if="block.exerciseType === 'match'" :block="block"/>
                <SelectExercisesBlock v-if="block.exerciseType === 'select'" :block="block"/>
                <SortExercisesBlock v-if="block.exerciseType === 'sort'" :block="block"/>
            </template>
        </template>
    </div>
</template>
