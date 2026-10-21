<script setup>
import ActivityContainer from "../../../components/ActivityContainer.vue";
import {onMounted, watch} from "vue";
import {useActivityViewer} from "../../../composables/activities/useActivityViewer.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import AppTip from "../../../components/AppTip.vue";

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

    <LoadingSpinner v-if="isLoadingActivity"/>
    <AppTip v-else-if="activityNotFound">
        <p>{{ $t('pages.common.not-found', {model: $t('actions.models.activity')}) }}</p>
    </AppTip>
    <div v-else-if="activity" id="app-body">
        <ActivityContainer :model="activity"/>
    </div>
</template>
