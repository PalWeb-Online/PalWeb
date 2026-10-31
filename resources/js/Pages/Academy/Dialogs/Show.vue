<script setup>
import {onMounted, watch} from 'vue';
import DialogContainer from '../../../components/DialogContainer.vue';
import {useDialogViewer} from '../../../composables/dialogs/useDialogViewer.js';
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const props = defineProps({
    dialogId: {
        type: Number,
        required: true,
    },
});

const {
    dialog,
    isLoadingDialog,
    loadDialog,
    reloadDialog,
} = useDialogViewer();

onMounted(() => loadDialog(props.dialogId));

watch(
    () => props.dialogId,
    () => reloadDialog(props.dialogId),
);
</script>
<template>
    <LoadingWrapper :is-loading="isLoadingDialog || !dialog">
        <div id="app-body">
            <DialogContainer :model="dialog"/>
        </div>
    </LoadingWrapper>
</template>
