<script setup>
import {ref} from 'vue';
import ModalWrapper from "./ModalWrapper.vue";

const props = defineProps({
    title: {type: String, default: ''},
    trigger: {type: String, default: 'manual'},
    type: {type: String, default: 'help'},
});

const isDialogVisible = ref(false);

const openDialog = () => {
    isDialogVisible.value = true;
};

const closeDialog = () => {
    isDialogVisible.value = false;
};

defineExpose({
    openDialog,
    closeDialog,
});
</script>

<template>
    <button v-if="trigger === 'manual'" class="material-symbols-rounded" @click="isDialogVisible = true">help</button>

    <ModalWrapper v-model="isDialogVisible">
        <div class="window-container modal-container help-container">
            <div class="window-section-head">
                <h2>{{ type }}: {{ title }}</h2>
                <button class="material-symbols-rounded" @click="isDialogVisible = false">close</button>
            </div>
            <div class="modal-container-body">
                <slot/>
            </div>
        </div>
    </ModalWrapper>
</template>
