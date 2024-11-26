<script setup>
import {computed} from 'vue';

const props = defineProps({
    value: {type: Number, default: 0},
    disabled: {type: Boolean, default: false}
});

const progressPercentage = computed(() => {
    return Math.min(Math.max(props.value, 0), 100);
});
</script>

<template>
    <div class="wizard-progress-container">
        <div class="wizard-progress-bar" :style="{ width: `${progressPercentage}%` }"></div>
    </div>
</template>
