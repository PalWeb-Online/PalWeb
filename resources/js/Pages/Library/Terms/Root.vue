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
        <Link :href="route('terms.index')"><h1>Dictionary</h1></Link>
    </div>
    <div id="app-body">
        <div class="nav-body">
            <Link :href="route('terms.index')"><- to All</Link>
        </div>
        <RootContainer :root="root"/>
    </div>
</template>
