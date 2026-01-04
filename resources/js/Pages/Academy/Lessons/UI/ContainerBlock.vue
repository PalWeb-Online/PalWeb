<script setup>
import SentenceBlock from "./SentenceBlock.vue";
import ChartBlock from "./ChartBlock.vue";
import TextBlock from "./TextBlock.vue";
import SentenceItem from "../../../../components/SentenceItem.vue";
import {inject, ref} from "vue";

defineProps({
    container: Object
})

const lessonSentences = inject('lessonSentences');
const isOpen = ref(false);
</script>

<template>
    <div class="block--container">
        <div class="block--container-head">
            <button class="material-symbols-rounded" @click="isOpen = !isOpen">
                {{ isOpen ? 'collapse_content' : 'expand_content' }}
            </button>
            <h2>{{ container.title }}</h2>
        </div>
        <template v-if="isOpen" v-for="block in container.blocks">
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
        </template>
    </div>
</template>

<style scoped>

</style>
