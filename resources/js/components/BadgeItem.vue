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
    <div :class="['badge-item', badge.unlocked ? '' : 'disabled']"
         @mousemove="showTooltip({
             title: badge.unlocked ? badge.title : '???',
             description: badge.description
         }, $event)"
         @mouseleave="hideTooltip()"
    >
        <img ref="element" :alt="badge.title" :src="`/img/badges/${badge.key}.svg`"/>
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
    transform-style: preserve-3d;
    transform: perspective(960rem);
    filter: drop-shadow(0 0.4rem 0.4rem rgb(0 0 0 / 0.33));

    img {
        width: 100%;
        height: 100%;
    }

    &.disabled {
        filter: grayscale(1);
        opacity: 0.5;
    }
}
</style>

