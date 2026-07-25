<script setup>
import {onMounted, watch} from "vue";
import Layout from "../../../Shared/Layout.vue";
import DialogContainer from "../../../components/DialogContainer.vue";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import {useDialogViewer} from "../../../composables/dialogs/useDialogViewer.js";
import AppTip from "../../../components/AppTip.vue";

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

    <LoadingSpinner v-if="isLoadingDialog"/>
    <AppTip v-else-if="dialogNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.dialog')}) }}</p>
    </AppTip>
    <div v-else-if="dialog" id="app-body">
        <DialogContainer :model="dialog"/>
    </div>
</template>
