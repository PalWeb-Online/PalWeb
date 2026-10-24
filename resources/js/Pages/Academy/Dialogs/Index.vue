<script setup>
import Paginator from "../../../Shared/Paginator.vue";
import DialogItem from "../../../components/DialogItem.vue";
import {onMounted, ref} from "vue";

const dialogs = ref({data: [], meta: {links: []}});

onMounted(async () => {
    const {data} = await axios.get('/api/dialogs');
    dialogs.value = data.dialogs;
});
</script>
<template>
    <div id="app-head">
        <RouterLink :to="{ name: 'dialogs.index' }"><h1>{{ $t('pages.dialogs.index.title') }}</h1></RouterLink>
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
