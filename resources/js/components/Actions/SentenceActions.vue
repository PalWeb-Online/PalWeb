<script setup>
import {route} from 'ziggy-js';
import {router} from '@inertiajs/vue3';
import {useUserStore} from "../../stores/UserStore.js";
import ContextActions from "./ContextActions.vue";

const props = defineProps({
    model: Object,
    icon: {type: String, default: 'symbol'},
});

const UserStore = useUserStore();

const deleteSentence = () => {
    if (!confirm('Are you sure you want to delete this Sentence?')) return;

    router.delete(route('sentences.destroy', props.model.id));
};
</script>

<template>
    <ContextActions :icon="icon" v-slot="{ closeMenu }">
        <Link :href="route('sentences.show', model.id)" role="menuitem" tabindex="-1">
            View Sentence
        </Link>

        <template v-if="UserStore.isAdmin">
            <Link :href="route('speech-maker.sentence', model.id)" role="menuitem" tabindex="-1">
                Edit Sentence
            </Link>
            <button @click="deleteSentence" role="menuitem" tabindex="-1">
                Delete Sentence
            </button>
        </template>
    </ContextActions>
</template>
