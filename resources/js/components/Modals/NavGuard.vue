<script setup>
const props = defineProps({
    message: {type: String, default: ''},
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <div class="window-container modal-container help-container">
        <div class="window-section-head">
            <h2>warning: unsaved changes</h2>
        </div>
        <div class="modal-container-body">
            <img src="/img/warning.svg" alt="Warning" style="width: 9.6rem; justify-self: center;"/>
            <p>{{ message }}</p>
        </div>
        <div class="window-footer">
            <button @click="$emit('confirm')">Confirm</button>
            <button @click="$emit('cancel')">Cancel</button>
        </div>
    </div>
</template>

