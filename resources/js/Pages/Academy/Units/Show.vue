<script setup>
import Layout from "../../../Shared/Layout.vue";
import LessonItem from "../../../components/LessonItem.vue";
import UnitNav from "./UI/UnitNav.vue";
import {useUserStore} from "../../../stores/UserStore.js";

const UserStore = useUserStore();

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
        <template v-for="lesson in lessons" :key="lesson.id">
            <LessonItem v-if="lesson.published || UserStore.isAdmin" :lesson="lesson"/>
        </template>
    </div>
</template>
