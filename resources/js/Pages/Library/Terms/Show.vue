<script setup>
import Layout from "../../../Shared/Layout.vue";
import TermContainer from "../../../components/TermContainer.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    terms: Object,
});
</script>
<template>
    <Head :title="`Dictionary: ${terms[0].term} (${terms[0].translit})`"/>
    <div id="app-body">
        <TermContainer v-for="term in terms" :key="term.id" :model="term"/>
    </div>
</template>
