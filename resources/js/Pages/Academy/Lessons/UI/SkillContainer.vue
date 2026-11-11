<script setup>
import {ref} from 'vue';
import DocumentBlocksRenderer from '../../../../components/Blocks/Renderers/DocumentBlocksRenderer.vue';
import AppTooltip from '../../../../components/AppTooltip.vue';

defineProps({
    skills: {
        type: Object,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: true,
    },
});

const tooltip = ref(null);
const isOpen = ref(false);
const selectedSkill = ref(null);

function selectSkill(skill) {
    if (selectedSkill.value === skill) {
        selectedSkill.value = null;
        isOpen.value = false;
        return;
    }

    selectedSkill.value = skill;
    isOpen.value = true;
}
</script>
<template>
    <div class="skill-container" :class="{ disabled }">
        <div class="skill-nav-wrapper"
             @mousemove="disabled && tooltip.showTooltip($t('Advance to Stage 2 to unlock the Skills!'), $event);"
             @mouseleave="disabled && tooltip.hideTooltip()"
        >
            <button v-for="(skill, i) in skills" :class="{ active: selectedSkill === skill }"
                    @click="selectSkill(skill)" :disabled="disabled">
<!--                                {{ i + 1 }}-->
                {{ skill.type }}
            </button>
        </div>

        <template v-if="!disabled">
            <div v-if="isOpen && selectedSkill" class="skill-body">
                <h1>{{ selectedSkill?.title }}</h1>
                <h2>{{ selectedSkill?.description }}</h2>
                <DocumentBlocksRenderer :blocks="selectedSkill.blocks ?? []"/>
            </div>
        </template>
    </div>
    <AppTooltip ref="tooltip"/>
</template>

<style scoped lang="scss">
.skill-container {
    &.disabled {
        cursor: not-allowed;
    }
}

.skill-nav-wrapper {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.2rem;
    background: var(--color-medium-secondary);
    border-block: 0.2rem solid var(--color-medium-secondary);

    button {
        font-family: var(--head-font);
        font-size: 2.4rem;
        font-weight: 700;
        padding: 1.6rem;
        background: var(--color-medium-primary);
        user-select: none;
        cursor: pointer;
        color: white;

        &.active {
            background: var(--color-medium-secondary);
        }

        &[disabled] {
            pointer-events: none;
            opacity: 0.5;
        }
    }
}


.skill-body {
    display: grid;
    font-size: 1.8rem;
    text-align: justify;
    background: white;
    margin: 0;
    position: relative;
    overflow: hidden;
    border-block-end: 0.2rem solid var(--color-medium-secondary);

    h1 {
        font-size: clamp(4.8rem, 10vw, 6.4rem);
        color: var(--color-medium-primary);
        margin: 0;
        padding: 3.2rem;
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
</style>
