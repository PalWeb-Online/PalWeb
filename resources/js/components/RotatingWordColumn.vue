<script setup>
import {ref, computed, onMounted, onBeforeUnmount} from 'vue';

const props = defineProps({
    words: Array,
});

const activeIndex = ref(0);

const columnStyle = computed(() => ({
    transform: `translateY(-${activeIndex.value * 1.5}em)`,
    transition: 'transform 0.5s cubic-bezier(0.56,0.07,0.35,1.2)',
}));

let intervalId = null;

onMounted(() => {
    intervalId = setInterval(() => {
        activeIndex.value = (activeIndex.value + 1) % props.words.length;
    }, 2000);
});

onBeforeUnmount(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <span class="rotating-word-container">
        <span class="rotating-word-viewport">
        <span class="rotating-word-column" :style="columnStyle" aria-live="polite">
            <span class="rotating-word" v-for="(word, i) in words" :key="i">
                {{ word }}
            </span>
        </span>
        </span>
    </span>
</template>

<style scoped>
.rotating-word-container {
    display: inline-block;
    font-size: clamp(1.8rem, 4vw, 3.2rem);
    height: calc(1.5em + 0.25em*2);
    line-height: 1em;
    overflow: hidden;
    vertical-align: middle;
    margin-block-end: 0.0625em;
    border-radius: 0.25em;
    background: white;
    padding-block: 0.25em
}

.rotating-word-viewport {
    display: block;
    height: 100%;
    width: 100%;
    padding-inline: 0.75em;
    mask-image: linear-gradient(to bottom, transparent 0%, black 15%, black 85%, transparent 100%);
}

.rotating-word-column {
    display: flex;
    flex-direction: column;
}

.rotating-word {
    height: 1.5em;
    line-height: 1.5em;
    padding-block-start: 0.03125em;
    color: var(--color-medium-secondary);
}
</style>
