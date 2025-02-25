<script setup>
import Layout from "../../../Shared/Layout.vue";
import SentenceContainer from "../../../components/SentenceContainer.vue";
import Paginator from "../../../Shared/Paginator.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    sentences: Object
});
</script>
<template>
    <Head title="Library: Sentences"/>
    <div id="app-head">
        <h1>Sentences</h1>
    </div>
    <div id="app-body">
        <div class="sentences-list">
            <SentenceContainer v-for="sentence in sentences.data" :key="sentence.id" :model="sentence"/>
        </div>
        <Paginator :links="sentences.meta.links"/>
    </div>
</template>
