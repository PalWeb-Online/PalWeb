<script setup>
import Layout from "../../../Shared/Layout.vue";
import LessonItem from "../../../components/LessonItem.vue";
import UnitNav from "./UI/UnitNav.vue";
import {useUnitViewer} from "../../../composables/units/useUnitViewer.js";
import LoadingSpinner from "../../../Shared/LoadingSpinner.vue";
import AppTip from "../../../components/AppTip.vue";
import {onMounted, watch} from "vue";

defineOptions({
    layout: Layout,
})

const props = defineProps({
    unitId: {
        type: Number,
        required: true,
    },
})

const {
    unit,
    unitNotFound,
    isLoadingUnit,
    lessons,
    loadUnit,
    reloadUnit,
} = useUnitViewer();

onMounted(async () => {
    await loadUnit(props.unitId);
});

watch(() => props.unitId, async () => {
    await reloadUnit(props.unitId);
});
</script>
<template>
    <Head :title="`Academy: Unit ${unit?.position}`"/>

    <LoadingSpinner v-if="isLoadingUnit"/>
    <template v-else-if="unitNotFound">
        <AppTip>
            <p>Sorry, but the requested Unit could not be found.</p>
        </AppTip>
    </template>
    <template v-else-if="unit">
        <div id="lesson-nav">
            <UnitNav :unit="unit"/>
        </div>
        <div id="app-body">
            <LessonItem v-for="lesson in lessons" :key="lesson.id" :lesson="lesson"/>
        </div>
    </template>
</template>
