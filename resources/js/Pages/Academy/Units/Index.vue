<script setup>
import Layout from "../../../Shared/Layout.vue";
import UnitNav from "./UI/UnitNav.vue";
import {route} from "ziggy-js";
import {useUserStore} from "../../../stores/UserStore.js";
import LessonItem from "../../../components/LessonItem.vue";

const UserStore = useUserStore();

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
        <h1>academy</h1>
    </div>
    <div id="lesson-nav" style="margin-block: 4.8rem 9.6rem;">
        <Link v-if="UserStore.isAdmin" :href="route('lesson-planner.index')" class="lesson-planner featured-title m">
            Lesson Planner
        </Link>
        <UnitNav v-for="unit in units" :key="unit.id" :unit="unit"/>
        <LessonItem v-for="lesson in lessons" :key="lesson.id" :lesson="lesson"/>
    </div>
</template>
