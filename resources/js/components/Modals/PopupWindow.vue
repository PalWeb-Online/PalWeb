<script setup>
import {ref} from 'vue';
import ModalWrapper from "./ModalWrapper.vue";

const props = defineProps({
    title: {type: String, default: ''},
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
    <span style="display: flex; align-items: center" @click="isDialogVisible = true">
        <slot name="trigger"/>
    </span>

    <ModalWrapper v-model="isDialogVisible">
        <div class="popup-window">
            <div class="window-head">
                <div class="tutorial-window-title">
                    <span class="material-symbols-rounded">info</span> {{ title }}
                </div>
                <div class="material-symbols-rounded" @click="isDialogVisible = false">close</div>
            </div>
            <div class="tutorial-window-body">
                <slot name="content"/>
            </div>
        </div>
    </ModalWrapper>
</template>
