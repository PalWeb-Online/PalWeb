<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import ContextActions from "./ContextActions.vue";
import {useUserStore} from "../../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    model: Object,
});

const deleteDialog = () => {
    if (!confirm('Are you sure you want to delete this Dialog?')) return;

    router.delete(route('dialogs.destroy', props.model.id));
};
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link v-if="$page.component !== 'Academy/Dialogs/Show'"
              :href="route('dialogs.show', model.id)" role="menuitem" tabindex="-1">
            View Dialog
        </Link>
        <template v-if="UserStore.isAdmin">
            <Link :href="route('speech-maker.dialog', model.id)" role="menuitem" tabindex="-1">
                Edit Dialog
            </Link>
            <button @click="deleteDialog" role="menuitem" tabindex="-1">
                Delete Dialog
            </button>
        </template>
        <Link v-if="model.lesson" :href="route('lessons.show', model.lesson.global_position)" role="menuitem"
              tabindex="-1">
            View Lesson
        </Link>
    </ContextActions>
</template>
