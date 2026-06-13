<script setup>
import {onMounted, ref} from 'vue';
import VanillaTilt from 'vanilla-tilt';
import {useTooltip} from "../composables/useTooltip.js";

const props = defineProps({
    badge: Object,
});

const element = ref(null);

onMounted(() => {
    if (props.badge.unlocked) {
        VanillaTilt.init(element.value, {
            max: 20,
            speed: 400,
            scale: 1,
            glare: true,
        });
    }
});

const {
    isVisible,
    tooltipStyle,
    tooltipData,
    showTooltip,
    hideTooltip
} = useTooltip();
</script>

<template>
    <div ref="element" :class="['badge-item', badge.unlocked ? '' : 'disabled']"
         @mousemove="showTooltip({
             title: badge.unlocked ? badge.name : '???',
             description: badge.description
         }, $event)"
         @mouseleave="hideTooltip()"
    >
        <img :alt="badge.name" :src="`/img/badges/${badge.image}`"/>
    </div>
    <div v-if="isVisible" :style="tooltipStyle" class="data-tooltip">
        <div style="font-weight: 700">{{ tooltipData.title }}</div>
        <div style="font-size: 1.2rem">{{ tooltipData.description }}</div>
    </div>
</template>

<style scoped lang="scss">
.badge-item {
    display: grid;
    border-radius: 50%;
    background: var(--color-accent-light);
    transform-style: preserve-3d;
    transform: perspective(960rem);

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: translateZ(1.6rem)
    }

    &.disabled {
        filter: grayscale(1);

        img {
            transform: scale(1.1);
        }
    }
}
</style>

