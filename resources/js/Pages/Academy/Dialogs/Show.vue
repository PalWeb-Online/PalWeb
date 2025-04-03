<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
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
    <div id="app-head">
        <Link :href="route('dialogs.index')"><h1>Dialogs</h1></Link>
    </div>
    <div id="app-body">
        <DialogContainer :model="dialog"/>
    </div>
</template>
