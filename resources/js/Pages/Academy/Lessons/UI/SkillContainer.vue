<script setup>
import {inject, ref} from "vue";
import SentenceItem from "../../../../components/SentenceItem.vue";
import SentenceBlock from "./SentenceBlock.vue";
import ChartBlock from "./ChartBlock.vue";
import TextBlock from "./TextBlock.vue";
import ContainerBlock from "./ContainerBlock.vue";

defineProps({
    skill: {type: Object, required: true}
})

const lessonSentences = inject('lessonSentences');
const isOpen = ref(false);
</script>
<template>
    <div class="skill-container">
        <div class="skill-head" @click="isOpen = !isOpen">
            <div class="featured-title s">skill: {{ skill.type }}</div>
        </div>

        <div v-if="isOpen" class="skill-body">
            <h1>{{ skill.title }}</h1>

            <template v-for="block in skill.blocks">
                <template v-if="block.type === 'text'">
                    <TextBlock :block="block"/>
                </template>
                <template v-if="block.type === 'sentence'">
                    <SentenceItem v-if="block.model" :model="lessonSentences[block.model.id]"/>
                    <SentenceBlock v-else :sentence="block.custom"/>
                </template>
                <template v-if="block.type === 'chart'">
                    <ChartBlock :chart="block"/>
                </template>

                <ContainerBlock :container="block" v-if="block.type === 'container'"/>
            </template>
        </div>
    </div>
</template>
