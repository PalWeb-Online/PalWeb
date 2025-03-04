<script setup>
import Layout from "../../../Shared/Layout.vue";
import Paginator from "../../../Shared/Paginator.vue";
import DialogItem from "../../../components/DialogItem.vue";

defineOptions({
    layout: Layout
});

defineProps({
    dialogs: Object,
});
</script>
<template>
    <Head title="Academy: Dialogs"/>
    <div id="app-head">
        <h1>Dialogs</h1>
    </div>
    <div id="app-body">
        <div class="decks-list">
            <DialogItem v-for="dialog in dialogs.data" :key="dialog.id" :model="dialog"/>
        </div>
        <Paginator :links="dialogs.meta.links"/>
    </div>
</template>
