<script setup>
import Layout from "../../../Shared/Layout.vue";
import DialogContainer from "../../../components/DialogContainer.vue";

defineOptions({
    layout: Layout
});

defineProps({
    dialog: Object,
});
</script>
<template>
    <Head :title="`Academy: Dialogs: ${dialog.title}`"/>
    <div id="app-body">
        <DialogContainer :model="dialog"/>
    </div>
</template>
