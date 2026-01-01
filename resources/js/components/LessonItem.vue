<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";
import WindowSection from "./WindowSection.vue";

const UserStore = useUserStore();

const props = defineProps({
    lesson: Object,
})
</script>

<template>
    <div class="window-container lesson-item"
         :class="{hidden: !lesson.published}">
        <div class="window-section-head">
            <h1>lesson {{ lesson.global_position }}</h1>
            <Link v-if="UserStore.isAdmin" :href="route('lesson-planner.lesson', lesson.id)" class="material-symbols-rounded">
                edit
            </Link>
        </div>
        <div class="window-content-head" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="window-content-head-title">{{ lesson.title }}</div>
            <div class="lesson-item-progress">
                <div class="material-symbols-rounded" :class="{ completed: lesson.progress.stage > 1}">check</div>
                <div class="material-symbols-rounded" :class="{ completed: lesson.progress.stage > 2}">check</div>
            </div>
        </div>

        <WindowSection :visible="false">
            <template #title>
                <div class="window-section-head">
                    <h2>skills</h2>
                </div>
            </template>
            <template #content>
                <div class="lesson-item-skills">
                    <div class="lesson-item-skill-wrapper" v-for="skill in lesson.document.skills">
                        <div class="lesson-skill-type featured-title">{{ skill.type }}</div>
                        <div class="lesson-skill-title">{{ skill.title }}</div>
                        <div class="lesson-skill-description">{{ skill.description }}</div>
                    </div>
                </div>
            </template>
        </WindowSection>
        <div class="window-footer">
            <Link :href="route('lessons.show', lesson.global_position)" :class="{ disabled: !UserStore.isAdmin && !UserStore.hasUnlockedLesson(lesson.id) }">
                open lesson
            </Link>
        </div>
    </div>
</template>
