<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    model: Object,
});

const deleteActivity = () => {
    if (!confirm('Are you sure you want to delete this Activity?')) return;

    router.delete(route('activities.destroy', props.model.id));
};
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link :href="route('lesson-planner.lesson-activity', model.lesson.id)" role="menuitem" tabindex="-1">
            Edit Activity
        </Link>
        <button @click="deleteActivity" role="menuitem" tabindex="-1">
            Delete Activity
        </button>
    </ContextActions>
</template>
