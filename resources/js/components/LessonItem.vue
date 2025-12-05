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
         :class="{ locked: !UserStore.isAdmin && !UserStore.user.lessons.includes(lesson.slug) }">
        <div class="window-section-head">
            <h1>lesson {{ lesson.slug }}</h1>
            <Link :href="route('lessons.show', lesson.slug)" class="material-symbols-rounded">
                door_open
            </Link>
        </div>
        <div class="window-content-head" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="window-content-head-title">{{ lesson.title }}</div>
            <div class="lesson-item-progress">
                <div class="material-symbols-rounded" :class="{ completed: lesson.progress.stage > 1}">check</div>
                <div class="material-symbols-rounded" :class="{ completed: lesson.progress.stage > 2}">check</div>
            </div>
        </div>

        <div class="lesson-model-progress-wrapper">
            <div class="featured-title l" style="flex-grow: 1">deck</div>
            <div v-if="lesson.deck">
                <div class="lesson-model-score-count">
                    <div class="material-symbols-rounded"
                         :class="{ completed: lesson.progress.scores_count?.deck > 0 }">check
                    </div>
                    <div class="material-symbols-rounded"
                         :class="{ completed: lesson.progress.scores_count?.deck > 1 }">check
                    </div>
                    <div class="material-symbols-rounded"
                         :class="{ completed: lesson.progress.scores_count?.deck > 2 }">check
                    </div>
                </div>
                <Link :href="route('deck-master.study', lesson.deck.id)">study</Link>
            </div>
            <template v-else>
                No Deck assigned to this Lesson yet.
            </template>
        </div>

        <WindowSection :visible="false">
            <template #title>
                <div class="window-section-head">
                    <h2>skills</h2>
                </div>
            </template>
            <template #content>
                <div class="lesson-item-skills">
                    <div class="lesson-item-skill-wrapper" v-for="skill in lesson.skills">
                        <div class="lesson-skill-type featured-title">{{ skill.type }}</div>
                        <div class="lesson-skill-title">{{ skill.title }}</div>
                        <div class="lesson-skill-description">{{ skill.description }}</div>
                    </div>
                </div>
            </template>
        </WindowSection>
    </div>
</template>
