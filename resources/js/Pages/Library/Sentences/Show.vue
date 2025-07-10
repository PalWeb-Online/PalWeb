<script setup>
import Layout from "../../../Shared/Layout.vue";
import SentenceContainer from "../../../components/SentenceContainer.vue";

defineOptions({
    layout: Layout
});

defineProps({
    sentence: Object
});
</script>
<template>
    <Head :title="`Library: Phrasebook: ${sentence.sentence}`"/>
    <div id="app-body">
        <SentenceContainer :model="sentence"/>
    </div>
</template>
