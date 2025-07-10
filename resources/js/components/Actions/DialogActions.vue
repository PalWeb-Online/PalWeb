<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    model: Object,
    icon: {type: String, default: 'symbol'},
});

const UserStore = useUserStore();

const deleteDialog = () => {
    if (!confirm('Are you sure you want to delete this Dialog?')) return;

    router.delete(route('dialogs.destroy', props.model.id));
};
</script>

<template>
    <ContextActions :icon="icon" v-slot="{ closeMenu }">
        <Link :href="route('dialogs.show', model.id)" role="menuitem" tabindex="-1">
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
    </ContextActions>
</template>
