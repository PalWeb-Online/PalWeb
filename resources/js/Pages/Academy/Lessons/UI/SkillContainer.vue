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

<style scoped lang="scss">
.skill-container {
    width: min(100%, 96rem);
    font-size: 1.8rem;
    text-align: justify;
    background: white;
    margin: 0;
    position: relative;
    overflow: hidden;
    border-block-end: 0.4rem solid var(--color-accent-medium);

    @media (min-width: 960px) {
        border: none;
        border-radius: 2.4rem;
    }

    p {
        margin: 0
    }

    .block--text {
        padding-inline: 3.2rem;
        margin-block: 3.2rem;

        p:not(:last-child) {
            margin-block-end: 2.4rem;
        }
    }

    & > *:last-child {
        margin-bottom: 0;
    }

    & > .popout {
        width: 6.4rem;
    }

    .skill-head {
        padding: 2.4rem 3.2rem;
        background: var(--color-medium-primary);
        user-select: none;
        cursor: pointer;

        .featured-title {
            color: white;
        }
    }

    .skill-body {
        display: grid;

        h1 {
            font-size: clamp(4.8rem, 10vw, 6.4rem);
            color: var(--color-medium-primary);
            margin: 0;
            padding: 2.4rem 3.2rem;
            background: var(--color-pastel-light);
            line-height: 1em;
            font-family: var(--head-font), serif;
            text-transform: none;
            direction: rtl;
        }

        .app-tip, .sentence-item-container {
            margin-inline: 3.2rem;
            margin-block-end: 3.2rem;
        }

        .app-tip {
            width: auto;
        }

        .sentence-item-container {
            margin-block-start: 1.6rem;
            justify-self: center;

        }

        & > *:last-child:not(.inflection-carousel) {
            margin-block-end: 6.4rem;
        }
    }
}
</style>
