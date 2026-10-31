<script setup>
import ActivityContainer from '../../../components/ActivityContainer.vue';
import {onMounted, watch} from 'vue';
import {useActivityViewer} from '../../../composables/activities/useActivityViewer.js';
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const props = defineProps({
    activityId: {
        type: Number,
        required: true,
    },
});

const {
    activity,
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
    <LoadingWrapper :is-loading="isLoadingActivity || !activity">
        <div id="app-body">
            <ActivityContainer :model="activity"/>
        </div>
    </LoadingWrapper>
</template>
