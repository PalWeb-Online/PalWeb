<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from 'ziggy-js';
import RootContainer from "../../../components/RootContainer.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    root: Object,
});
</script>
<template>
    <Head :title="`Dictionary: ${root.ar} (${root.en})`"/>
    <div id="app-head">
        <h1>Dictionary</h1>
    </div>
    <div id="app-body">
        <Link :href="route('terms.index')"><- to All</Link>
        <RootContainer :root="root"/>
    </div>
</template>
