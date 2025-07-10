<script setup>
import Layout from "../../../Shared/Layout.vue";
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
    <div id="app-body">
        <RootContainer :root="root"/>
    </div>
</template>
