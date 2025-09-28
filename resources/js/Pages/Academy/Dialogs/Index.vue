<script setup>
import Layout from "../../../Shared/Layout.vue";
import Paginator from "../../../Shared/Paginator.vue";
import DialogItem from "../../../components/DialogItem.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import AppButton from "../../../components/AppButton.vue";
import {useUserStore} from "../../../stores/UserStore.js";

const UserStore = useUserStore();

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
        <Link :href="route('dialogs.index')"><h1>Dialogs</h1></Link>
        <AppButton v-if="UserStore.isAdmin" label="Create New" @click="router.get(route('speech-maker.dialog'))"/>
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
