<script setup>
import {computed} from 'vue';

const props = defineProps({
    value: {type: Number, default: 0},
});

const progressPercentage = computed(() => {
    return Math.min(Math.max(props.value, 0), 100);
});
</script>

<template>
    <div class="wizard-progress-container">
        <div :class="{ 'wizard-progress-bar': true, 'complete': progressPercentage === 100 }" :style="{ width: `${progressPercentage}%` }"></div>
    </div>
</template>
