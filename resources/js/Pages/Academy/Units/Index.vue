<script setup>
import Layout from "../../../Shared/Layout.vue";
import UnitNav from "./UI/UnitNav.vue";
import LessonItem from "../../../components/LessonItem.vue";

defineOptions({
    layout: Layout,
})

defineProps({
    units: Object,
    lessons: Object
})
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="app-head">
        <h1>{{ $t('pages.academy.index.title') }}</h1>
    </div>
    <div id="lesson-nav" style="margin-block: 4.8rem 9.6rem;">
        <UnitNav v-for="unit in units" :key="unit.id" :unit="unit"/>
        <LessonItem v-for="lesson in lessons" :key="lesson.id" :lesson="lesson"/>
    </div>
</template>
