<script setup>
import {route} from 'ziggy-js';
import ContextActions from "./ContextActions.vue";
import {useUserStore} from "../../stores/UserStore.js";
import {useDialog} from "../../composables/dialogs/useDialog.js";

const UserStore = useUserStore();
const {deleteDialog} = useDialog();

const props = defineProps({
    model: Object,
});
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
            <button @click="deleteDialog(model)" role="menuitem" tabindex="-1">
                Delete Dialog
            </button>
        </template>
        <Link v-if="model.lesson" :href="route('lessons.show', model.lesson.global_position)" role="menuitem"
              tabindex="-1">
            View Lesson
        </Link>
    </ContextActions>
</template>
