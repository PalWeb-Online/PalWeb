<script setup>
import Layout from "../../../Shared/Layout.vue";
import Paginator from "../../../Shared/Paginator.vue";
import DialogItem from "../../../components/DialogItem.vue";
import {route} from "ziggy-js";

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
        <Link :href="route('dialogs.index')"><h1>{{ $t('pages.dialogs.index.title') }}</h1></Link>
    </div>
    <div id="app-body">
        <div class="app-body-section">
            <div class="model-list">
                <DialogItem v-for="dialog in dialogs.data" :key="dialog.id" :model="dialog"/>
            </div>
            <Paginator :links="dialogs.meta.links"/>
        </div>
    </div>
</template>
