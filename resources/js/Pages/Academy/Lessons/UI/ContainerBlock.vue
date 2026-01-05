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

<style scoped lang="scss">
.block--container {
    display: grid;
    margin: 4.8rem 3.2rem 3.2rem;
    border-radius: 1.6rem;
    background: var(--color-accent-light);

    .block--container-head {
        justify-self: start;
        display: flex;
        align-items: center;
        gap: 1.6rem;
        margin: 0 1.6rem -1.6rem;
        transform: translateY(-2.4rem);
        user-select: none;

        .material-symbols-rounded {
            height: 3.2rem;
            width: 3.2rem;
            border-radius: 50%;
            color: white;
            background: var(--color-medium-primary);
            font-size: 2.4rem;
        }

        h2 {
            font-family: var(--head-font), serif;
            font-weight: 700;
            font-size: 3.2rem;
            color: var(--color-dark-primary);
            text-transform: none;
            margin: 0
        }

        & + * {
            margin-block-start: 0.8rem;
        }
    }
}
</style>
