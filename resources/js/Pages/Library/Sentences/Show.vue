<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from 'ziggy-js';
import SentenceContainer from "../../../components/SentenceContainer.vue";
import {router} from "@inertiajs/vue3";

defineOptions({
    layout: Layout
});

defineProps({
    sentence: Object
});
</script>
<template>
    <Head :title="`Library: Phrasebook: ${sentence.sentence}`"/>
    <div id="app-head">
        <Link :href="route('sentences.index')"><h1>Phrasebook</h1></Link>
    </div>
    <div id="app-body">
        <div class="nav-body">
            <Link :href="route('sentences.index')"><- to All</Link>
            <Link :href="route('sentences.random')">to Random -></Link>
        </div>
        <SentenceContainer :model="sentence" size="l" dialog/>
    </div>
</template>
