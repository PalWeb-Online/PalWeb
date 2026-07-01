<script setup>
import {route} from 'ziggy-js';
import ContextActions from "./ContextActions.vue";
import {useUserStore} from "../../stores/UserStore.js";
import {useActivity} from "../../composables/activities/useActivity.js";

const UserStore = useUserStore();
const {deleteActivity} = useActivity();

const props = defineProps({
    model: Object,
});
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link v-if="$page.component !== 'Academy/Activities/Show'"
              :href="route('activities.show', model.id)" role="menuitem" tabindex="-1">
            View Activity
        </Link>
        <template v-if="UserStore.isAdmin">
            <Link :href="route('lesson-planner.lesson-activity', model.lesson.id)" role="menuitem" tabindex="-1">
                Edit Activity
            </Link>
            <button @click="deleteActivity(model)" role="menuitem" tabindex="-1">
                Delete Activity
            </button>
        </template>
        <Link v-if="model.lesson"
              :href="route('lessons.show', model.lesson.global_position)" role="menuitem" tabindex="-1">
            View Lesson
        </Link>
        <Link :href="route('scores.history', { scorable_type: 'activity', scorable_id: model.id })" role="menuitem"
              tabindex="-1">
            View Scores
        </Link>
    </ContextActions>
</template>
