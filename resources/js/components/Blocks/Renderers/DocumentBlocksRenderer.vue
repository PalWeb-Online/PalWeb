<script setup>
import SentenceItem from "../../../components/SentenceItem.vue";
import HeadingBlock from "../../../components/Blocks/Renderers/HeadingBlock.vue";
import ContainerBlock from "./ContainerBlock.vue";
import TextBlock from "./TextBlock.vue";
import AudioBlock from "./AudioBlock.vue";
import ChartBlock from "./ChartBlock.vue";
import TableBlock from "./TableBlock.vue";
import SentenceBlock from "./SentenceBlock.vue";
import InputExercisesBlock from "./InputExercisesBlock.vue";
import MatchExercisesBlock from "./MatchExercisesBlock.vue";
import SelectExercisesBlock from "./SelectExercisesBlock.vue";

const props = defineProps({
    blocks: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <template v-for="block in blocks" :key="block.id">
        <ContainerBlock v-if="block.type === 'container'" :block="block">
            <DocumentBlocksRenderer
                :blocks="block.blocks ?? []"
            />
        </ContainerBlock>

        <HeadingBlock v-else-if="block.type === 'heading'" :block="block"/>
        <TextBlock v-else-if="block.type === 'text'" :block="block"/>
        <AudioBlock v-else-if="block.type === 'audio'" :block="block"/>
        <ChartBlock v-else-if="block.type === 'chart'" :block="block"/>
        <TableBlock v-else-if="block.type === 'table'" :block="block"/>

        <template v-else-if="block.type === 'sentence'">
            <SentenceItem v-if="block.model" :model="block.model"/>
            <SentenceBlock v-else :sentence="block.custom"/>
        </template>

        <template v-else-if="block.type === 'exercises'">
            <InputExercisesBlock v-if="block.exerciseType === 'input'" :block="block"/>
            <MatchExercisesBlock v-else-if="block.exerciseType === 'match'" :block="block"/>
            <SelectExercisesBlock v-else-if="block.exerciseType === 'select'" :block="block"/>
            <p v-else>Unsupported Exercise Block type: {{ block.exerciseType }}</p>
        </template>

        <p v-else>Unsupported Block: {{ block.type }}</p>
    </template>
</template>

<style scoped lang="scss">

</style>
