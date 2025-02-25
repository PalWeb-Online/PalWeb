<script setup>
import Layout from "../../../Shared/Layout.vue";
import PronunciationItem from "../../../components/PronunciationItem.vue";
import Paginator from "../../../Shared/Paginator.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    audios: Object
});
</script>
<template>
    <Head title="Library: Audios"/>
    <div id="app-head">
        <h1>Audios</h1>
    </div>
    <div id="app-body">
        <div class="audios-list">
            <PronunciationItem v-for="audio in audios.data" :model="audio.pronunciation" :audio="audio"/>
        </div>
        <Paginator :links="audios.meta.links"/>
    </div>
</template>
