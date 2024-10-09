<script setup>
import {computed} from 'vue';

const props = defineProps({
    value: {type: Number, default: 0}, // Progress value between 0 and 100
    disabled: {type: Boolean, default: false}
});

// Compute the progress percentage based on the value prop
const progressPercentage = computed(() => {
    return Math.min(Math.max(props.value, 0), 100); // Ensure it's between 0 and 100
});
</script>

<template>
    <div class="progress-bar-container">
        <div class="progress-bar" :style="{ width: `${progressPercentage}%` }"></div>
    </div>
</template>

<style scoped>
.progress-bar-container {
    width: 100%;
    background-color: #e0e0e0;
    border-radius: 4px;
    height: 20px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background-color: #007bff;
    transition: width 0.3s ease;
}
</style>
