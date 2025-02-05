<script setup>
const props = defineProps({
    message: {
        type: String,
        default: 'Are you sure?',
    },
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <div class="app-dialog-overlay">
        <div class="app-dialog">
            <div class="app-dialog-body">
                <p>{{ message }}</p>
                <div class="app-alert-actions">
                    <button @click="$emit('cancel')">Cancel</button>
                    <button @click="$emit('confirm')">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.app-alert-actions {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 10px;
    width: 100%;
}
</style>

