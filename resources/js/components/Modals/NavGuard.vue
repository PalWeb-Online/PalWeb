<script setup>
const props = defineProps({
    message: {type: String, default: ''},
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <div class="window-container modal-container help-container">
        <div class="window-section-head">
            <h2>{{ $t('modals.nav-guard.title') }}</h2>
        </div>
        <div class="modal-container-body">
            <img src="/img/warning.svg" :alt="$t('modals.nav-guard.warning-icon')" style="width: 9.6rem; justify-self: center;"/>
            <p>{{ message }}</p>
        </div>
        <div class="window-footer">
            <button @click="$emit('confirm')">{{ $t('modals.nav-guard.confirm') }}</button>
            <button @click="$emit('cancel')">{{ $t('modals.nav-guard.cancel') }}</button>
        </div>
    </div>
</template>
