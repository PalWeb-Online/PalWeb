<script setup>
import draggable from 'vuedraggable';

const themes = [
    'theme-Watermelon',
    'theme-Jerusalem',
    'theme-PalWebOS',
    'theme-Msaxxan',
    'theme-Nabatean',
    'theme-Falastin',
]
</script>

<template>
    <draggable class="theme-testing" :list="themes">
        <template #item="{element}">
            <div class="theme-preview-bar" :class="element">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </template>
    </draggable>
</template>

<style scoped lang="scss">
.theme-testing {
    display: grid;
    height: 100vh;
}

.theme-preview-bar {
    display: grid;
    grid-template-columns: repeat(10, 1fr);

    & > *:nth-child(1) {
        background: var(--color-dark-secondary);
    }

    & > *:nth-child(2) {
        background: var(--color-dark-primary);
    }

    & > *:nth-child(3) {
        background: var(--color-medium-secondary);
    }

    & > *:nth-child(4) {
        background: var(--color-medium-primary);
    }

    & > *:nth-child(5) {
        background: var(--color-pastel-dark);
    }

    & > *:nth-child(6) {
        background: var(--color-pastel-medium);
    }

    & > *:nth-child(7) {
        background: var(--color-pastel-light);
    }

    & > *:nth-child(8) {
        background: var(--color-accent-dark);
    }

    & > *:nth-child(9) {
        background: var(--color-accent-medium);
    }

    & > *:nth-child(10) {
        background: var(--color-accent-light);
    }
}
</style>
