<script setup>
import SentenceItem from "../../../components/SentenceItem.vue";
import SentenceBlock from "../../../Pages/Academy/Lessons/UI/SentenceBlock.vue";
import ChartBlock from "../../../Pages/Academy/Lessons/UI/ChartBlock.vue";
import TextBlock from "../../../Pages/Academy/Lessons/UI/TextBlock.vue";
import ContainerBlock from "../../../Pages/Academy/Lessons/UI/ContainerBlock.vue";
import HeadingBlock from "../../../components/Blocks/Renderers/HeadingBlock.vue";
import MatchExercisesBlock from "../../../Pages/Academy/Lessons/UI/MatchExercisesBlock.vue";
import InputExercisesBlock from "../../../Pages/Academy/Lessons/UI/InputExercisesBlock.vue";
import SelectExercisesBlock from "../../../Pages/Academy/Lessons/UI/SelectExercisesBlock.vue";
import TableBlock from "../../../Pages/Academy/Lessons/UI/TableBlock.vue";
import AudioBlock from "../../../Pages/Academy/Lessons/UI/AudioBlock.vue";

const props = defineProps({
    blocks: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <template v-for="block in blocks" :key="block.id">
        <ContainerBlock v-if="block.type === 'container'" :container="block">
            <DocumentBlocksRenderer
                :blocks="block.blocks ?? []"
            />
        </ContainerBlock>

        <HeadingBlock v-else-if="block.type === 'heading'" :block="block"/>
        <TextBlock v-else-if="block.type === 'text'" :block="block"/>
        <AudioBlock v-else-if="block.type === 'audio'" :block="block"/>
        <ChartBlock v-else-if="block.type === 'chart'" :chart="block"/>
        <TableBlock v-else-if="block.type === 'table'" :table="block"/>

        <template v-else-if="block.type === 'sentence'">
            <SentenceItem v-if="block.model" :model="block.model"/>
            <SentenceBlock v-else :sentence="block.custom"/>
        </template>

        <template v-else-if="block.type === 'exercises'">
            <InputExercisesBlock v-if="block.exerciseType === 'input'" :block="block"/>
            <MatchExercisesBlock v-if="block.exerciseType === 'match'" :block="block"/>
            <SelectExercisesBlock v-if="block.exerciseType === 'select'" :block="block"/>
            <p v-else>Unsupported Exercise Block type: {{ block.exerciseType }}</p>
        </template>

        <p v-else>Unsupported Block: {{ block.type }}</p>
    </template>
</template>

<style scoped lang="scss">

</style>
