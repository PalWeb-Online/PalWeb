<script setup>
import Layout from "../../../Shared/Layout.vue";
import ActivityContainer from "../../../components/ActivityContainer.vue";

defineOptions({
    layout: Layout
});

defineProps({
    activity: Object,
})
</script>

<template>
    <Head title="Academy: Activities"/>
    <div id="app-body">
        <ActivityContainer :model="activity"/>
    </div>
</template>
