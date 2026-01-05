<script setup>
import {route} from "ziggy-js";
import WindowSection from "./WindowSection.vue";
import ActivityActions from "./Actions/ActivityActions.vue";
import ScoreStats from "./ScoreStats.vue";
import LessonStatus from "./LessonStatus.vue";

defineProps({
    model: {type: Object, required: false}
})
</script>

<template>
    <div class="window-container">
        <div class="window-header">
            <div class="material-symbols-rounded">home</div>
            <div class="window-header-url">www.palweb.app/academy/lessons/{lesson}</div>
        </div>
        <div class="window-section-head">
            <h1>activity</h1>
            <ActivityActions :model="model"/>
        </div>

        <LessonStatus v-if="model.lesson" :lesson="model.lesson" :model="model"/>
        <div v-else class="window-content-head">
            <div class="window-content-head-title">{{ model.title }}</div>
        </div>

        <WindowSection>
            <template #title>
                <h2>stats</h2>
            </template>
            <template #content>
                <ScoreStats :model="model"/>
            </template>
        </WindowSection>

        <div class="window-footer">
            <Link :href="route('activities.activity', model.id)">
                Start Activity
            </Link>
        </div>
    </div>
</template>

<style scoped>

</style>
