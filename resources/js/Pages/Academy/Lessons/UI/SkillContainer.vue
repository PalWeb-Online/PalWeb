<script setup>
import {ref} from "vue";
import DocumentBlocksRenderer from "../../../../components/Blocks/Renderers/DocumentBlocksRenderer.vue";

defineProps({
    skill: {type: Object, required: true}
})

const isOpen = ref(false);
</script>
<template>
    <div class="skill-container">
        <div class="skill-head" @click="isOpen = !isOpen">
            <div class="featured-title s">skill: {{ skill.type }}</div>
        </div>

        <div v-if="isOpen" class="skill-body">
            <h1>{{ skill.title }}</h1>
            <h2>{{ skill.description }}</h2>

            <DocumentBlocksRenderer :blocks="skill.blocks ?? []"/>
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

    @media (width >= 960px) {
        border: none;
        border-radius: 2.4rem;
    }

    & > *:last-child {
        margin-bottom: 0;
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

        h2 {
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            margin: 3.2rem 3.2rem 0;
            padding: 0;
            font-family: var(--head-font), serif;
            color: var(--color-medium-primary);
            text-transform: none;
        }

        .app-tip {
            margin-inline: 3.2rem;
            margin-block-end: 3.2rem;
            width: auto;
        }

        & > *:last-child:not(.inflection-carousel) {
            margin-block-end: 6.4rem;
        }
    }
}
</style>
