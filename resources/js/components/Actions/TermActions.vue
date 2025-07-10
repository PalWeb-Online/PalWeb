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

const deleteTerm = () => {
    if (!confirm('Are you sure you want to delete this Term?')) return;

    router.delete(route('terms.destroy', props.model.id));
};
</script>

<template>
    <ContextActions :icon="icon" v-slot="{ closeMenu }">
        <Link :href="route('terms.show', model.slug)" role="menuitem" tabindex="-1">
            View Term
        </Link>

        <template v-if="UserStore.isAdmin">
            <Link :href="route('terms.edit', model.id)" role="menuitem" tabindex="-1">
                Edit Term
            </Link>
            <button @click="deleteTerm" role="menuitem" tabindex="-1">
                Delete Term
            </button>
        </template>
    </ContextActions>
</template>
