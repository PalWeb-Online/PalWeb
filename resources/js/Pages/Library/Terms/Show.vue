<script setup>
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";
import {route} from 'ziggy-js';
import {Link} from '@inertiajs/inertia-vue3'
import RootContainer from "../../../components/RootContainer.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    root: Object,
    terms: Object,
});
</script>
<template>
    <Head :title="`Dictionary: ${terms[0].term} (${terms[0].translit})`"/>
    <div id="app-head">
        <h1>Dictionary</h1>
    </div>
    <div id="app-body">
        <Link :href="route('terms.index')"><- to All</Link>
        <TermContainer v-for="term in terms" :key="term.id" :model="term" :root="root"/>
        <RootContainer v-if="root" :root="root" :terms="terms"/>
    </div>
</template>
