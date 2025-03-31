<script setup>
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";
import {route} from 'ziggy-js';
import {router} from "@inertiajs/vue3";

defineOptions({
    layout: Layout
});

const props = defineProps({
    terms: Object,
});
</script>
<template>
    <Head :title="`Dictionary: ${terms[0].term} (${terms[0].translit})`"/>
    <div id="app-head">
        <h1>Dictionary</h1>
        <button class="feature-callout" @click="router.get(route('terms.random'))">I'm Feeling Lucky!</button>
    </div>
    <div id="app-body">
        <Link :href="route('terms.index')"><- to All</Link>
        <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
    </div>
</template>
