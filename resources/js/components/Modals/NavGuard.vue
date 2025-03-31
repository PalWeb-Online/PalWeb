<script setup>
import AppButton from "../AppButton.vue";

const props = defineProps({
    message: {
        type: String,
        default: 'Are you sure?',
    },
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <div class="modal-container-wrapper">
        <div class="modal-heading">warning</div>
        <div class="modal-container">
            <img src="/img/warning.svg" alt="Warning" style="width: 9.6rem; justify-self: center;"/>
            <p>{{ message }}</p>
            <div class="app-alert-actions">
                <AppButton @click="$emit('cancel')" label="Cancel"/>
                <AppButton @click="$emit('confirm')" label="Confirm"/>
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

