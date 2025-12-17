<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";

const props = defineProps({
    unit: Object,
    activeSlug: {type: String, required: false},
})

const UserStore = useUserStore();
</script>

<template>
    <div class="unit-progress-wrapper">
        <Link :href="route('units.index')"
              v-if="$page.component !== 'Academy/Units/Index'"
        ><- to Academy
        </Link>
        <div class="featured-title m">unit {{ unit.id }}: {{ unit.title }}</div>
        <div class="unit-meta">
            <div>{{ unit.published ? '' : 'not' }} published</div>
            <Link :href="route('lesson-planner.unit', unit)">edit</Link>
        </div>
        <div class="unit-progress-bar-wrapper">
            <Link :href="route('units.show', unit)" class="material-symbols-rounded"
                  :class="{ active: $page.component === 'Academy/Lessons/Unit' }">
                home
            </Link>
            <div class="unit-progress-bar">
                <Link v-for="lesson in unit.lessons"
                      :href="route('lessons.show', lesson)"
                      :class="{
                        locked: !UserStore.isAdmin && !lesson.unlocked,
                        unlocked: UserStore.isAdmin || lesson.unlocked,
                        completed: lesson.completed,
                        active: activeSlug === lesson.slug,
                        hidden: !lesson.published
                      }"
                >
                    {{ lesson.slug }}
                </Link>
                <Link v-if="unit.lessons.length < 9"
                      :href="route('lesson-planner.unit-lesson', unit)">+
                </Link>
            </div>
            <div class="material-symbols-rounded checkmark"
                 :class="{completed: !unit.lessons.some(lesson => !lesson.completed)}">
                check
            </div>
        </div>
    </div>
</template>
