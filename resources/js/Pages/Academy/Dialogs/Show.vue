<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from "ziggy-js";
import {Link} from "@inertiajs/inertia-vue3";
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
        <h1>Dialogs</h1>
    </div>
    <div id="app-body">
        <Link :href="route('dialogs.index')"><- to All</Link>
        <DialogContainer :model="dialog"/>
    </div>
</template>
