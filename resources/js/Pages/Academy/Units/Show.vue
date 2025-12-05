<script setup>
import Layout from "../../../Shared/Layout.vue";
import LessonItem from "../../../components/LessonItem.vue";
import UnitNav from "./UI/UnitNav.vue";

defineOptions({
    layout: Layout,
})

defineProps({
    unit: Object,
    lessons: Object
})
</script>
<template>
    <Head title="Academy: Lessons"/>
    <div id="lesson-nav">
        <UnitNav :unit="unit"/>
    </div>
    <div id="app-body">
        <LessonItem v-for="lesson in lessons" :lesson="lesson" :key="lesson.id"/>
    </div>
</template>
