<script setup>
import {computed} from "vue";
import {getHeadingAnchorId} from "../headingAnchors.js";

const props = defineProps({
    block: {type: Object, required: true}
});

const headingLevel = computed(() => {
    return ['h1', 'h2', 'h3'].includes(props.block.level)
        ? props.block.level
        : 'h2';
});

const headingId = computed(() => getHeadingAnchorId(props.block));
</script>

<template>
    <div :id="headingId" class="block--heading">
        <component :is="headingLevel">
            {{ props.block.title }}
        </component>
    </div>
</template>

<style scoped lang="scss">
h1, h2, h3 {
    color: var(--color-dark-primary);
    font-family: var(--head-font), sans-serif;
    text-transform: none;
    font-weight: 700;
    text-box: trim-both cap alphabetic;
    background: var(--color-accent-light);
    margin: 1em 0 0;
    padding-block-start: 0.5em;
    padding-inline-start: 1.2rem;
}

h1 {
    font-size: 3.2rem;
}

h2 {
    font-size: 2.4rem;
}

h3 {
    font-size: 2.0rem;
}
</style>
