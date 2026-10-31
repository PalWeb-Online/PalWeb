<script setup>
import LessonItem from "../../../components/LessonItem.vue";
import UnitNav from "./UI/UnitNav.vue";
import {useUnitViewer} from "../../../composables/units/useUnitViewer.js";
import {onMounted, watch} from "vue";
import LoadingWrapper from '../../../Shared/LoadingWrapper.vue';

const props = defineProps({
    unitId: {
        type: Number,
        required: true,
    },
})

const {
    unit,
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
    <UnitNav v-if="unit" :unit="unit"/>

    <LoadingWrapper :is-loading="isLoadingUnit || !unit">
        <div id="app-body">
            <LessonItem v-for="lesson in lessons" :key="lesson.id" :lesson="lesson"/>
        </div>
    </LoadingWrapper>
</template>
