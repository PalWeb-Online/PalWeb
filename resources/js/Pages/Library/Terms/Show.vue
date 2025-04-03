<script setup>
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";
import {route} from 'ziggy-js';

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
        <Link :href="route('terms.index')"><h1>Dictionary</h1></Link>
    </div>
    <div id="app-body">
        <div class="nav-body">
            <Link :href="route('terms.index')"><- to All</Link>
            <Link :href="route('terms.random')">to Random -></Link>
        </div>
        <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
    </div>
</template>
