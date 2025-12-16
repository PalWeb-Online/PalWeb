<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3'
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    model: Object,
});

const deleteTerm = () => {
    if (!confirm('Are you sure you want to delete this Term?')) return;

    router.delete(route('terms.destroy', props.model.id));
};
</script>

<template>
    <ContextActions v-slot="{ closeMenu }">
        <Link :href="route('word-logger.term', model.id)" role="menuitem" tabindex="-1">
            Edit Term
        </Link>
        <button @click="deleteTerm" role="menuitem" tabindex="-1">
            Delete Term
        </button>
    </ContextActions>
</template>
