<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../../../../stores/UserStore.js";
import {computed} from "vue";
import {router, usePage} from "@inertiajs/vue3";

const UserStore = useUserStore();

const props = defineProps({
    unit: Object,
    lesson: {type: Object, required: false},
    activeLesson: {type: String, required: false},
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
            <Link :href="route('units.show', unit.position)" class="material-symbols-rounded"
                  :class="{ active: $page.component === 'Academy/Units/Show' }">
                home
            </Link>
            <div class="unit-progress-bar">
                <Link v-for="lesson in unit.lessons"
                      :href="route('lessons.show', lesson)"
                      :class="{
                        locked: !UserStore.isAdmin && !UserStore.hasUnlockedLesson(lesson.id),
                        unlocked: UserStore.isAdmin || UserStore.hasUnlockedLesson(lesson.id),
                        completed: lesson.completed,
                        active: activeLesson === lesson.global_position,
                        hidden: !UserStore.isAdmin && !lesson.published
                      }"
                >
                    {{ lesson.global_position }}
                </Link>
                <Link v-if="UserStore.isAdmin && unit.lessons?.length < 9"
                      :href="route('lesson-planner.unit-lesson', unit)">+
                </Link>
            </div>
            <div class="material-symbols-rounded checkmark"
                 :class="{completed: !unit.lessons?.some(lsn => !lsn.completed)}">
                check
            </div>
        </div>
    </div>
</template>
