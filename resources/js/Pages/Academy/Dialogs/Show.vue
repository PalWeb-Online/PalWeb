<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import DialogContainer from "../../../components/DialogContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useDialogViewer} from "../../../composables/dialogs/useDialogViewer.js";

defineOptions({
    layout: Layout
});

const props = defineProps({
    dialogId: {
        type: Number,
        required: true,
    },
});

const {
    dialog,
    dialogNotFound,
    isLoadingDialog,
    loadDialog,
    reloadDialog,
} = useDialogViewer();

onMounted(() => loadDialog(props.dialogId));

watch(
    () => props.dialogId,
    () => reloadDialog(props.dialogId)
);
</script>
<template>
    <Head :title="dialog ? `Academy: Dialogs: ${dialog.title}` : 'Academy: Dialogs'"/>
    <div id="app-body">
        <LoadingSpinner v-if="isLoadingDialog"/>
        <DialogContainer v-else-if="dialog" :model="dialog"/>
        <div v-else-if="dialogNotFound" class="loading-state"><p>Unable to load Dialog.</p></div>
    </div>
</template>
