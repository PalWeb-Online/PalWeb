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
        <div class="app-notification-title">{{ type }}</div>
        <div class="app-notification-message">
            {{ message }}
        </div>
    </div>
</template>
