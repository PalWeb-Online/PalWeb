<script setup>
import Layout from "../../../Shared/Layout.vue";
import ActivityContainer from "../../../components/ActivityContainer.vue";
import {onMounted, watch} from "vue";
import {useActivityViewer} from "../../../composables/activities/useActivityViewer.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import AppTip from "../../../components/AppTip.vue";

defineOptions({
    layout: Layout
});

const props = defineProps({
    activityId: {
        type: Number,
        required: true,
    },
})

const {
    activity,
    activityNotFound,
    isLoadingActivity,
    loadActivity,
    reloadActivity,
} = useActivityViewer();

onMounted(async () => {
    await loadActivity(props.activityId);
});

watch(() => props.activityId, async () => {
    await reloadActivity(props.activityId);
});
</script>

<template>
    <Head title="Academy: Activities"/>
    <LoadingSpinner v-if="isLoadingActivity"/>
    <template v-else-if="activityNotFound">
        <AppTip>
            <p>Sorry, but the requested Activity could not be found.</p>
        </AppTip>
    </template>
    <div v-else-if="activity" id="app-body">
        <ActivityContainer :model="activity"/>
    </div>
</template>
