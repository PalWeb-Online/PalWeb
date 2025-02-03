<script setup>
const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success',
    },
});
</script>

<template>
    <div class="app-notification" :class="type">
        {{ message }}
    </div>
</template>
