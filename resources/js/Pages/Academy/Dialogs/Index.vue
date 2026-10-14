<script setup>
import Layout from "../../../Shared/Layout.vue";
import Paginator from "../../../Shared/Paginator.vue";
import DialogItem from "../../../components/DialogItem.vue";
import {route} from "ziggy-js";
import {onMounted, ref} from "vue";

defineOptions({
    layout: Layout
});

const dialogs = ref({data: [], meta: {links: []}});

onMounted(async () => {
    const {data} = await axios.get(route('api.academy.dialogs.index'));
    dialogs.value = data.dialogs;
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
