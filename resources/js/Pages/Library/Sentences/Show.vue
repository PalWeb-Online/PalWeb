<script setup>
import Layout from "../../../Shared/Layout.vue";
import {route} from 'ziggy-js';
import SentenceContainer from "../../../components/SentenceContainer.vue";

defineOptions({
    layout: Layout
});

defineProps({
    sentence: Object
});
</script>
<template>
    <Head :title="`Library: Sentences: ${sentence.sentence}`"/>
    <div id="app-head">
        <h1>Sentences</h1>
    </div>
    <div id="app-body">
        <Link :href="route('sentences.index')"><- to All</Link>
        <SentenceContainer :model="sentence" size="l" dialog/>
    </div>
</template>
